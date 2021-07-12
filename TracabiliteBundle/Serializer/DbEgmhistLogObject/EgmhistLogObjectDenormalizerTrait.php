<?php
/**
 * User Seif <ben.s@mipih.fr>
 * Date time: 08/01/2019 20:45
 */

namespace Gta\TracabiliteBundle\Serializer\DbEgmhistLogObject;

use Gta\CoreBundle\Repository\BaseRepository;
use Gta\TracabiliteBundle\Entity\EgmhistLogObject;
use Symfony\Component\PropertyAccess\PropertyAccess;

/**
 * Trait DbEgmhistLogObjectDenormalizer
 * @package Gta\TracabiliteBundle\Serializer\Normalizer
 * @author  Seif <ben.s@mipih.fr>
 */
trait EgmhistLogObjectDenormalizerTrait
{
    /**
     * {@inheritdoc}
     * @return \Gta\TracabiliteBundle\Entity\EgmhistLogObject
     * @author Seif <ben.s@mipih.fr>
     */
    public function denormalize($data, $class, $format = null, array $context = array())
    {
        $accessorBuilder = PropertyAccess::createPropertyAccessorBuilder();
        $pa = $accessorBuilder
//            ->enableExceptionOnInvalidIndex()
            ->getPropertyAccessor();
        $extra = $pa->getValue($data, '[extra]');
        $nomuti = $pa->getValue($data, '[extra][nomuti]');
        $servic = $pa->getValue($data, '[extra][main_filter][mf_servic]');
        $sertyp = $pa->getValue($data, '[extra][main_filter][mf_sertyp]');
        $typtab = $pa->getValue($data, '[extra][main_filter][mf_typtab]');
        $codAct = $pa->getValue($data, '[context][codAct]');
        $codFct = $pa->getValue($data, '[context][codFct]');

        // paramètres construits par le user pour le déclenchement de l'event
        // en général ce sont les paramètres passés à notre DB
        $ucParams = $this->removeParamsPlaceholdres(
            $pa->getValue($data, '[context][params]')
        );
        // Construction objet
        $dbEgmhistLogObject = new EgmhistLogObject();
        $dbEgmhistLogObject
            ->setCodhop($this->extractCodhop($ucParams, $extra))
            ->setNomuti($nomuti ? $nomuti : '_GOST')
            ->setMatric($this->extractMatric($ucParams))
            ->setUcParams($ucParams)
            ->setServic($servic)
            ->setCodAct($codAct)
            ->setCodFct($codFct)
            ->setSertyp($sertyp)
            ->setTyptab($typtab ? $typtab : ' ')
            ->setDatdeb($this->extractDatDeb($ucParams))
            ->setDatfin($this->extractDatFin($ucParams));

        return $dbEgmhistLogObject;
    }

    /**
     * {@inheritdoc}
     * @author Seif <ben.s@mipih.fr>
     */
    public function supportsDenormalization($data, $type, $format = null)
    {
        return $this->supportsAll($type);
    }

    /**
     * @param $params
     *
     * @return mixed
     * @author Seif <ben.s@mipih.fr>
     */
    private function extractDatDeb($params)
    {
        foreach (self::DATE_DEB_KEYS as $key) {
            if (array_key_exists($key, $params)) {
                return $params[$key];
            }
        }
    }

    /**
     * @param $params
     *
     * @return array
     * @author Seif <ben.s@mipih.fr>
     */
    private function removeParamsPlaceholdres($params)
    {
        $transformedParams = [];
        foreach ($params as $key => $param) {
            $cleanedKey = $key;
            if (BaseRepository::DEFAULT_PLACE_HOLDER == substr($key, 0, 1)) {
                $cleanedKey = str_replace(BaseRepository::DEFAULT_PLACE_HOLDER, '', $key);
            }
            $transformedParams[$cleanedKey] = $param;
        }

        return $transformedParams;
    }

    /**
     * @param $params
     *
     * @return mixed
     * @author Seif <ben.s@mipih.fr>
     */
    private function extractDatFin($params)
    {
        foreach (self::DATE_FIN_KEYS as $key) {
            if (array_key_exists($key, $params)) {
                $datfin = $params[$key];
                break;
            }
        }
        if (!isset($datfin)) {
            return '31/12/3000';
        }

        return $datfin;
    }

    /**
     * @param $params
     * @param $extra
     *
     * @return mixed
     * @author Seif <ben.s@mipih.fr>
     */
    private function extractCodhop($params, $extra)
    {
        if (isset($extra['codhop'])) {
            return $extra['codhop'];
        }

        if (isset($params['codhop'])) {
            return $params['codhop'];
        }

        if (isset($params[':codhop'])) {
            return $params[':codhop'];
        }
    }

    /**
     * @param $params
     *
     * @return mixed
     * @author Seif <ben.s@mipih.fr>
     */
    private function extractMatric($params)
    {
        $keys = ['matric', ':matric', 'ts_matric', 'matcou'];
        foreach ($keys as $key) {
            if (isset($params[$key]) && 0 !== strlen(strval($params[$key]))) {
                return $params[$key];
            }
        }

        return null;
    }
}