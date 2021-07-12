<?php
/**
 * User Seif <ben.s@mipih.fr>
 * Date time: 15/03/2019 18:20
 */

namespace Gta\Domain\Mapping;


use Gta\Domain\Mapping\Transformer\AbstractDataTransformer;
use Gta\Domain\Mapping\Transformer\AbstractTnoTransformer;
use Gta\Domain\Mapping\Mapper\AbstractMapper;
use Gta\Domain\Mapping\Mapper\DefaultMapper;

/**
 * Class DataMapper
 *
 * @package Gta\CoreBundle\Mapper
 * @author  Seif <ben.s@mipih.fr> (15/03/2019/ 19:15)
 * @version 19
 */
final class DataMapper
{
    private $transformers = array();

    /**
     * @param array       $data     Le tableau des données à mapper
     * @param array       $params   Les paramètres à envoyer aux lookers associés
     * @param string|null $dataType Nom de classe du mapper qui va s'occuper du mapping, obligatoirement
     *                              devra implémenter le Abstract Mapper
     * @param array       $extra    Les données extra
     *
     * @return array
     * @throws \Gta\CoreBundle\Exception\Mapping\MissingRubriqueTNOException
     * @throws \ReflectionException
     * @author Seif <ben.s@mipih.fr>
     */
    public function map(array $data, array $params = null, $dataType = null, array $extra = [])
    {
        // appliquer le mapper par défaut
        if (null === $dataType) {
            $dataType = DefaultMapper::class;
        }

        if (!class_exists($dataType)) {
            throw new \InvalidArgumentException(
                'You must provide a valid FCQN class name that implements '.AbstractMapper::class.' ' .$dataType.' provided (hint: use '.__CLASS__.' constants)'
            );
        }
        $reflection = new \ReflectionClass($dataType);
        if (!$reflection->isSubclassOf(AbstractMapper::class)) {
            throw new \InvalidArgumentException(
                'You must provide a class name implementing '.AbstractMapper::class.' (hint: use '.__CLASS__.' constants)'
            );
        }
        /**
         * @var $mapper AbstractMapper
         */
        $mapper = new $dataType();

        /**
         * @var $transformer AbstractDataTransformer
         */
        foreach ($this->transformers as $transformer) {
            if ($transformer->supports($dataType)) {

                // définir les paramètres
                $transformer->setParams($params);

                // pour les lookers de type TNO, il faut setter le Rubcod avant de initialiser les données!!
                if (isset($extra['rubcod']) && $transformer instanceof AbstractTnoTransformer) {
                    $transformer->setRubCod($extra['rubcod']);
                }
                // charger les données du looker
                $transformer->initData();

                // enregistrer le looker
                $mapper->addTransformer($transformer);
            }
        }
        $data = $mapper->applyTransformers($data);

        return $mapper->mapData($data, $extra);
    }

    /**
     * @param \Gta\Domain\Mapping\Transformer\AbstractDataTransformer $dataLookerTransformer
     *
     * @author Seif <ben.s@mipih.fr>
     */
    public function addTransformer(AbstractDataTransformer $dataLookerTransformer)
    {
        $this->transformers[get_class($dataLookerTransformer)] = $dataLookerTransformer;
    }

    /**
     * @param $id
     *
     * @return AbstractDataTransformer
     * @author Seif <ben.s@mipih.fr>
     */
    public function getTransformer($id)
    {
        if (isset($this->transformers[$id])) {
            return $this->transformers[$id];
        }

        return null;

    }

}