<?php
/**
 * Created by PhpStorm.
 * User: ditte.t
 * Date: 18/10/2019
 * Time: 16:58
 */

namespace Gta\Domain\ParamsEtab;

use Gta\CoreBundle\Contracts\Traits\ArrayAccessTrait;
use Gta\CoreBundle\ParamConverter\MainFilter;
use Gta\MedicalBundle\Repository\ParametersRepository;

/**
 * Trait ParamsEtabService
 * @package Gta\Domain\ParamsEtab
 * @author tditt <ditte.t@mipih.fr> (18/10/2019/ 16:59)
 * @version 19
 */
class ParamsEtabService implements \ArrayAccess
{
    use ArrayAccessTrait;
    /**
     * @var ParametersRepository
     */
    private $parameterRepository;
    /**
     * @var MainFilter
     */
    private $mainFilter;

    /**
     * ParamsEtabService constructor.
     * @param ParametersRepository $parametersRepository
     * @param MainFilter $mainFilter
     */
    public function __construct(ParametersRepository $parametersRepository, MainFilter $mainFilter)
    {
        $this->parameterRepository = $parametersRepository;
        $this->mainFilter = $mainFilter;
    }

    /**
     * @author tditt <ditte.t@mipih.fr>
     */
    public function loadEtabParams()
    {
        if (true === $this->mainFilter->isDefined()) {
            $this->store = $this->parameterRepository->gmpar_annex($this->mainFilter->toArray());
        }

    }

    public function getStore()
    {
        return $this->store;
    }

}