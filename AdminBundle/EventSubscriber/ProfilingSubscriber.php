<?php
/**
 * User Seif <ben.s@mipih.fr>
 * Date time: 28/08/2019 15:45
 */

namespace Gta\AdminBundle\EventSubscriber;


use Gta\AdminBundle\Controller\ProfilingController;
use Gta\CoreBundle\Event\GetControllerActionFromRequestTrait;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\HttpKernel\KernelInterface;

/**
 * Class ProfilingSubscriber
 *
 * @package Gta\AdminBundle\EventSubscriber
 * @author  Seif <ben.s@mipih.fr> (28/08/2019/ 16:14)
 * @version 19
 */
class ProfilingSubscriber implements EventSubscriberInterface
{
    use GetControllerActionFromRequestTrait;
    /**
     * @var \Symfony\Component\HttpKernel\Profiler\Profiler
     */
    private $profiler;
    /**
     * @var string
     */
    private $env;

    /**
     * ProfilingSubscriber constructor.
     *
     * @param \Symfony\Component\HttpKernel\KernelInterface $kernel
     */
    public function __construct(KernelInterface $kernel)
    {
        if ($kernel->getContainer()->has('profiler')) {
            $this->profiler = $kernel->getContainer()->get('profiler');
        }
        $this->env = $kernel->getEnvironment();

    }

    /**
     * {@inheritDoc}
     */
    public static function getSubscribedEvents()
    {
        return [
            KernelEvents::REQUEST => [
                ['disableProfiler', 0],
            ],
        ];

    }

    /**
     * @param \Symfony\Component\HttpKernel\Event\GetResponseEvent $event
     *
     * @author Seif <ben.s@mipih.fr>
     */
    public function disableProfiler(GetResponseEvent $event)
    {
        if (null === $this->profiler) {
            return;
        }
        $request = $event->getRequest();
        $controller = $this->getControllerActionNames($request)['controller'];
        if (ProfilingController::class === $controller) {
            if ('prod' === $this->env) {
                $event->setResponse(new Response('You are not allowed to see this in prod mode', 403));

                return;
            }
            $this->profiler->disable();
        }
    }
}