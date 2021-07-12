<?php
/**
 * This file is part of GTA api project.
 * (c) Mansour BERREKIA <berrekia.m@mipih.fr>
 * @author mberrekia <berrekia.m@mipih.fr>
 * Date 05/02/2020 on  14:56
 */

namespace Gta\DataExportBundle\Strategy;

use Gta\DataExportBundle\Planning\Model\TsModelInterface;

/**
 *
 * Class TsModelStrategy
 * @package Gta\DataExportBundle\Worksheet
 * @author  mberrekia <berrekia.m@mipih.fr>
 */
class TsModelStrategy
{
    /**
     * @var array
     * @author mberrekia <berrekia.m@mipih.fr>
     * Date 05/02/2020 on  18:11
     */
    private $models;


    /**
     * @param TsModelInterface $model
     *
     * @author mberrekia <berrekia.m@mipih.fr>
     * Date 05/02/2020 on  18:28
     */
    public function addModel(TsModelInterface $model)
    {
        $this->models[] = $model;
    }

    /**
     * @param array $data
     * @param array $params
     *
     * @return TsModelInterface
     */
    public function loadModel(array $data, array $params): TsModelInterface
    {
        $modelId = 'modelId';
        if (!isset($params[$modelId]) || !$params[$modelId] || !in_array($params[$modelId], TsModelInterface::MODELS)) {
            $params[$modelId] = '1';
            // throw new \InvalidArgumentException('No model ID was provided'); # une fois le DE sur le frotn résolu, faut bien restaurer cette ligne
        }

        /** @var TsModelInterface $model */
        foreach ($this->models as $model) {
            if ($model->supports($params[$modelId])) {

                return $model->init($data, $params);
            }
        }

        throw new \LogicException('Man!! No model found for  type '.$params[$modelId]);
    }
}