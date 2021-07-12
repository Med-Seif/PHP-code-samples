<?php
/**
 * User Seif <ben.s@mipih.fr>
 * Date time: 07/01/2019 16:26
 */

namespace Gta\TracabiliteBundle\Serializer\DbEgmhistLogObject;

use Gta\CoreBundle\Repository\BaseRepository;
use Gta\Domain\Lib\Std;
use Gta\TracabiliteBundle\Resources\StringConstants as Sc;

/**
 * Trait DbEgmhistLogObjectNormalizer
 * @package Gta\TracabiliteBundle\Serializer\Normalizer
 * @author  Seif <ben.s@mipih.fr>
 */
trait DbParamsNormalizerTrait
{
    public $counter = 1;

    /**
     * {@inheritdoc}
     * @param \Gta\TracabiliteBundle\Entity\EgmhistLogObject $dbEgmHistLogObject
     *
     * @return array
     * @author Seif <ben.s@mipih.fr>
     */
    public function normalize(
        $dbEgmHistLogObject,
        $format = null,
        array $context = array(),
        $ordre = 1
    ) {
        $parameters = [
            Sc::CODHOP => $dbEgmHistLogObject->getCodhop(),
            Sc::NOMUTI => $dbEgmHistLogObject->getNomuti(),
            Sc::MATRIC => $dbEgmHistLogObject->getMatric(),
            Sc::CODFCT => $dbEgmHistLogObject->getCodFct(),
            Sc::CODACT => $dbEgmHistLogObject->getCodAct(),
            Sc::DATDEB => $dbEgmHistLogObject->getDatdeb(),
            Sc::DATFIN => $dbEgmHistLogObject->getDatfin(),
            Sc::GMRUBR => $dbEgmHistLogObject->getGmrubr(),
            Sc::TYPTAB => $dbEgmHistLogObject->getTyptab(),
            Sc::SERVIC => $dbEgmHistLogObject->getServic(),
            Sc::SERTYP => $dbEgmHistLogObject->getSertyp(),
            Sc::VALUE => $dbEgmHistLogObject->getMessage()
        ];

        $placeholders = [];
        $dateKeys = [
            Sc::DATDEB,
            Sc::DATFIN,
        ];
        $params = array_keys($parameters);
        foreach ($params as $param) {
            if (in_array($param, $dateKeys)) {
                if (strlen($parameters[$param]) == 8) {
                    // si c'est un format anglais, convertir vers un format francais
                    $parameters[$param] = Std::convertDateEnToFr($parameters[$param]);
                }
                $placeholders [$param] = 'to_date('
                    .BaseRepository::DEFAULT_PLACE_HOLDER
                    .$param
                    .',\'DD/MM/YYYY HH24:MI:SS\')';
            } else {
                $placeholders[$param] = BaseRepository::DEFAULT_PLACE_HOLDER.$param;
            }
        }
        return [
            Sc::PARAMETERS_KEY    => $parameters,
            Sc::PLACE_HOLDERS_KEY => $placeholders,
        ];

    }

    /**
     * {@inheritdoc}
     * @author Seif <ben.s@mipih.fr>
     */
    public function supportsNormalization($data, $format = null)
    {
        return $this->supportsAll($data);
    }
}