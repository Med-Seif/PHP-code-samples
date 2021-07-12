<?php
/**
 * User Seif <ben.s@mipih.fr>
 * Date time: 11/04/2019 15:41
 */

namespace Gta\Domain\Mapping\Transformer;

use Gta\Domain\Mapping\Mapper\ActionMapper;
use Gta\MedicalBundle\Repository\EtatCivilRepository;

/**
 * Class EtatCivilTransformer
 *
 * @package Gta\Domain\Mapping\Transformer
 * @author  Seif <ben.s@mipih.fr> (11/04/2019/ 15:42)
 * @version 19
 */
class EtatCivilTransformer extends AbstractDataTransformer
{
    /**
     * @var \Gta\MedicalBundle\Repository\EtatCivilRepository
     */
    private $etatCivilRepository;
    /**
     * @var array
     */
    private $etatCivilData = [];

    /**
     * EtatCivilTransformer constructor.
     *
     * @param \Gta\MedicalBundle\Repository\EtatCivilRepository $etatCivilRepository
     */
    public function __construct(EtatCivilRepository $etatCivilRepository)
    {
        $this->etatCivilRepository = $etatCivilRepository;
    }

    /**
     * @return array
     */
    public function getEtatCivilData()
    {
        return $this->etatCivilData;
    }

    /**
     * @return mixed|void
     * @author Seif <ben.s@mipih.fr>
     */
    public function initData()
    {
        $this->etatCivilData = $this->etatCivilRepository->findAll($this->getParams());
    }

    /**
     * @param $class
     *
     * @return mixed
     * @author Seif <ben.s@mipih.fr>
     */
    public function supports($class)
    {
        return $class === ActionMapper::class;
    }

    /**
     * @param mixed $row
     *
     * @return mixed
     * @author Seif <ben.s@mipih.fr>
     */
    public function transform($row)
    {
        $transformerData = $this->getEtatCivilData();
        $row['name'] = isset($transformerData[$row['matric']]) ? $transformerData[$row['matric']] : ' ';

        return $row;
    }
}