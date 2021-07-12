<?php
/**
 * User Seif <ben.s@mipih.fr>
 * Date time: 15/03/2019 16:35
 */

namespace Gta\Domain\Mapping\Mapper;

use Gta\Domain\Mapping\Transformer\AbstractDataTransformer;

/**
 * Class AbstractMapper
 *
 * @package Gta\MedicalBundle\Mapper
 * @author  Seif <ben.s@mipih.fr> (15/03/2019/ 16:38)
 * @version 19
 */
abstract class AbstractMapper
{
    /**
     * @var array
     */
    private $transformers = [];

    /**
     * Blocage de la redéfinition du constructeur
     * AbstractMapper constructor.
     */
    final public function __construct()
    {
    }

    /**
     * @param array $data
     * @param array $extra
     *
     * @return array
     * @author Seif <ben.s@mipih.fr>
     */
    abstract public function mapData(array $data, array $extra = []);

    /**
     * @param \Gta\Domain\Mapping\Transformer\AbstractDataTransformer $dataTransformer
     *
     * @author Seif <ben.s@mipih.fr>
     */
    public function addTransformer(AbstractDataTransformer $dataTransformer)
    {
        $this->transformers[get_class($dataTransformer)] = $dataTransformer;
    }

    /**
     * @param $row
     *
     * @return mixed
     * @author Seif <ben.s@mipih.fr>
     */
    public function transform($row)
    {
        foreach ($this->transformers as $transformer) {
            $row = $transformer->transform($row);
        }

        return $row;
    }

    /**
     * @param array $row
     * @param array $keys
     *
     * @return array
     * @author Seif <ben.s@mipih.fr>
     */
    public function removeKeys(array $row, array $keys)
    {
        foreach ($keys as $key) {
            unset($row[$key]);
        }

        return $row;

    }

    /**
     * Application des Transformers
     *
     * @param array $data
     *
     * @return array
     * @author Seif <ben.s@mipih.fr>
     */
    public function applyTransformers(array $data)
    {
        $dataTransformed = array();
        foreach ($data as $key => $row) {
            $dataTransformed[$key] = $this->transform($row);
        }
        return $dataTransformed;
    }
}