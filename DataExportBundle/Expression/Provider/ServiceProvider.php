<?php
/**
 * User Seif <ben.s@mipih.fr>
 * Date time: 09/04/2019 19:57
 */

namespace Gta\DataExportBundle\Expression\Provider;


use Gta\CoreBundle\Resolver\ExtraOptionsResolver;
use Symfony\Component\ExpressionLanguage\ExpressionFunction;
use Symfony\Component\ExpressionLanguage\ExpressionFunctionProviderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class ServiceProvider
 *
 * @package Gta\CoreBundle\Expression\Provider
 * @author  Seif <ben.s@mipih.fr> (09/04/2019/ 19:58)
 * @version 19
 */
class ServiceProvider implements ExpressionFunctionProviderInterface
{

    /**
     * @var \Gta\CoreBundle\Resolver\ExtraOptionsResolver
     */
    private $resolver;

    /**
     * ServiceProvider constructor.
     *
     * @param \Gta\CoreBundle\Resolver\ExtraOptionsResolver $resolver
     */
    public function __construct(ExtraOptionsResolver $resolver)
    {
        $this->resolver = $resolver;
    }

    /**
     * @return ExpressionFunction[] An array of Function instances
     */
    public function getFunctions()
    {
        return array(
            new ExpressionFunction(
                'serviceLabel',
                function ($arguments) {
                }, [$this, 'serviceLabel'] // callable must be public
            ),
        );
    }

    /**
     * @param $arguments
     * @param $serviceIn
     * @param $showMessage
     *
     * @return string
     * @author Seif <ben.s@mipih.fr>
     */
    public function serviceLabel($arguments, $serviceIn, $showMessage = false)
    {

        $optionsResolver = $this->resolver;
        $servic = 'servic';
        $serlib = 'serlib';
        $optionsResolver->setDefaults(
            [
                'typtab'  => null,
                $servic   => null,
                'sertyp'  => null,
                $serlib   => null,
                'sercon'  => null,
                'secdlin' => null,
                'secdlme' => null,
            ]
        );

        $service = $optionsResolver->resolve($serviceIn);

        if (!$service[$servic] && true === $showMessage) {
            return 'Aucun service principal';
        }
        // laignement vertical des champs du service
        $serviceOut = [
            $service['typtab'],
            str_pad($service[$servic], 4, ' '),
            str_pad($service['sertyp'], 3, ' '),
        ];

        if (null !== $service[$serlib]) {
            $serviceOut[] = $service[$serlib];
        }

        return implode(' ', $serviceOut);
    }
}