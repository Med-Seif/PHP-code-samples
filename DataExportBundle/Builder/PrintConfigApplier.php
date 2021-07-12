<?php
/**
 * User Seif <ben.s@mipih.fr>
 * Date time: 27/02/2020 10:23
 */

namespace Gta\DataExportBundle\Builder;


use Gta\CoreBundle\Expression\GtaExpressionLanguage;
use Gta\CoreBundle\ParamConverter\MainFilter as MF;
use Gta\DataExportBundle\Adapters\ExportAdapterInterface;
use Gta\DataExportBundle\Utils\TsKey;
use Gta\Domain\DD\DataDictionary as DD;
use Gta\MedicalBundle\Repository\GmservRepository;

/**
 * Class PrintConfigApplier
 *
 * @package Gta\DataExportBundle\Builder
 * @author  Seif <ben.s@mipih.fr> (27/02/2020/ 10:35)
 * @version 19
 */
class PrintConfigApplier
{
    use ModelSetterTrait;
    /**
     * @var \Gta\DataExportBundle\Adapters\ExportAdapterInterface
     */
    private $adapter;
    /**
     * @var \Gta\CoreBundle\Expression\GtaExpressionLanguage
     */
    private $expressionLanguage;
    /**
     * @var \Gta\MedicalBundle\Repository\GmservRepository
     */
    private $gmservRepository;

    /**
     * PrintConfigApplier constructor.
     *
     * @param \Gta\DataExportBundle\Adapters\ExportAdapterInterface $adapter
     * @param \Gta\CoreBundle\Expression\GtaExpressionLanguage      $expressionLanguage
     * @param \Gta\MedicalBundle\Repository\GmservRepository        $gmservRepository
     */
    public function __construct(
        ExportAdapterInterface $adapter,
        GtaExpressionLanguage $expressionLanguage,
        GmservRepository $gmservRepository
    ) {
        $this->adapter = $adapter;
        $this->expressionLanguage = $expressionLanguage;
        $this->gmservRepository = $gmservRepository;
    }

    /**
     * @param array $params
     *
     * @throws \ReflectionException
     * @author Seif <ben.s@mipih.fr>
     */
    public function apply(array $params)
    {
        $printMethodCalls = YmlConfigArrayAccess::getConfig(TsKey::K_PRINT_METHOD_CALLS);
        $formattedPrintOptions = $this->formatPrintOptions($printMethodCalls->toArray(), $params);
        $methods = $this->getSetters();
        $this->applyPrintSettings($formattedPrintOptions, $methods);
        $this->applyLineBreakPrints();
    }

    /**
     * @param       $printOptions
     *
     * @param array $params
     *
     * @return array
     * @author Seif <ben.s@mipih.fr>
     */
    private function formatPrintOptions(array $printOptions, array $params)
    {
        $params_ = [
            DD::USER   => $params['name'],
            DD::CODHOP => $params[MF::MF_CODHOP],
            DD::SERVIC => $params[MF::MF_SERVIC],
            DD::TYPTAB => $params[MF::MF_TYPTAB],
            DD::SERTYP => $params[MF::MF_SERTYP],
            DD::DATDEB => $params[MF::MF_DATDEB],
            DD::DATFIN => $params[MF::MF_DATFIN],
            DD::LIBETB => $params['libetb'],
            DD::SERLIB => $this->gmservRepository->findService($params)['serlib'],
        ];
        $formattedPrintOptions = [];
        foreach ($printOptions as $optionKey => $optionVal) {
            # evaluate for print header and footer
            if (TsKey::K_PRINT_ALL_HEADER === $optionKey || TsKey::K_PRINT_ALL_FOOTER === $optionKey) {
                $optionVal = $this->expressionLanguage->evaluate($optionVal, $params_);
            }
            $formattedPrintOptions['set'.ucfirst($optionKey)] = $optionVal;
        }

        return $formattedPrintOptions;
    }

    /**
     * @return array
     * @throws \ReflectionException
     * @author Seif <ben.s@mipih.fr>
     */
    private function getSetters()
    {
        $reflection = new \ReflectionClass($this->adapter);
        $methods = [];
        foreach ($reflection->getMethods(\ReflectionMethod::IS_PUBLIC) as $optionKey) {
            $fName = $optionKey->getName();
            if ('set' === substr($fName, 0, 3)) {
                $methods [] = $optionKey->getName();
            }
        }

        return $methods;
    }

    /**
     * @param $formattedPrintOptions
     * @param $methods
     *
     * @author Seif <ben.s@mipih.fr>
     */
    private function applyPrintSettings($formattedPrintOptions, $methods)
    {
        foreach ($formattedPrintOptions as $methodName => $args) {
            if (!in_array($methodName, $methods)) {
                throw new \LogicException('Method '.$methodName.' is missing');
            }
            # multiple args are supported by default by the call_user_func_array
            if (!is_array($args)) {
                $args = (array)$args;
            }
            # go
            call_user_func_array([$this->adapter, $methodName], $args);
        }
    }

    /**
     * @author Seif <ben.s@mipih.fr>
     */
    private function applyLineBreakPrints()
    {
        $this->model->applyLineBreakPrints();
    }
}