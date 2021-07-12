<?php
/**
 * User Seif <ben.s@mipih.fr>
 * Date time: 27/02/2020 10:33
 */

namespace Gta\DataExportBundle\Builder;


use Gta\DataExportBundle\Planning\Model\TsModelInterface;

/**
 * Trait ModelSetterTrait
 * @package Gta\DataExportBundle\Builder
 * @author  Seif <ben.s@mipih.fr> (27/02/2020/ 10:34)
 * @version 19
 */
trait ModelSetterTrait
{
    /**
     * @var TsModelInterface
     */
    private $model;

    /**
     * @return \Gta\DataExportBundle\Planning\Model\TsModelInterface
     * @author Seif <ben.s@mipih.fr>
     */
    public function getModel(): TsModelInterface
    {
        if (!$this->model instanceof TsModelInterface) {
            throw new \InvalidArgumentException('You have to define model prioir to apply style, use setModel method');
        }

        return $this->model;
    }

    /**
     * @param \Gta\DataExportBundle\Planning\Model\TsModelInterface $model
     *
     * @return self
     * @author Seif <ben.s@mipih.fr>
     */
    public function setModel(TsModelInterface $model): self
    {
        $this->model = $model;

        return $this;
    }
}