<?php
/**
 * User Seif <ben.s@mipih.fr>
 * Date time: 15/03/2019 10:20
 */

namespace Gta\Domain\Mapping\Transformer;


use Gta\Domain\Mapping\Transformer\AbstractDataTransformer;
use Gta\Domain\Mapping\Mapper\ContratMapper;
use Gta\MedicalBundle\Repository\ServiceRepository;

/**
 * Class ServiceByMatricTransformer
 *
 * @package Gta\Domain\Mapping\Transformer
 * @author  Seif <ben.s@mipih.fr> (15/03/2019/ 17:45)
 * @version 19
 */
class ServiceByMatricTransformer extends AbstractDataTransformer
{
    /**
     * @var mixed
     */
    private $serviceByMatricData;
    /**
     * @var \Gta\MedicalBundle\Repository\ServiceRepository
     */
    private $serviceRepository;

    /**
     * ServiceByMatricTransformer constructor.
     *
     * @param \Gta\MedicalBundle\Repository\ServiceRepository $serviceRepository
     */
    public function __construct(ServiceRepository $serviceRepository)
    {
        $this->serviceRepository = $serviceRepository;
    }

    /**
     * @param $class
     *
     * @return bool|mixed
     * @author Seif <ben.s@mipih.fr>
     */
    public function supports($class)
    {
        return ContratMapper::class === $class;
    }

    /**
     * @param $row
     *
     * @return array
     * @author Seif <ben.s@mipih.fr>
     */
    public function transform($row)
    {
        $matric = $row['matric'];
        $resultArray = [
            'typtab' => null,
            'servic' => null,
            'sertyp' => null,
            'serlib' => null,
        ];
        $transformerData = $this->getServiceByMatricData();
        if (!isset($transformerData[$matric])) {
            return $row + $resultArray;
        }
        $byMatricData = $transformerData[$matric];

        return $row + [
                'typtab' => $byMatricData['typtab'],
                'servic' => str_pad($byMatricData['servic'], 4, ' '),
                'sertyp' => str_pad($byMatricData['sertyp'], 3, ' '),
                'serlib' => $byMatricData['serlib'],
            ];
    }

    /**
     * Sets data gathered from repositories (or wathever else) that will be used in look() method
     *
     * @return mixed
     * @author Seif <ben.s@mipih.fr>
     */
    public function initData()
    {
        // récupérer les services
        $rows = $this->serviceRepository->findByMatric($this->getParams());

        // mapper par matricule et retourner
        $this->serviceByMatricData = array_column($rows, null, 'matric');
    }

    /**
     * @return array
     * @author Seif <ben.s@mipih.fr>
     */
    private function getServiceByMatricData()
    {
        // le boulot est déjà fait
        return $this->serviceByMatricData;
    }
}