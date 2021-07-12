<?php
/**
 * User Seif <ben.s@mipih.fr>
 * Date time: 05/07/2019 18:29
 */

namespace Gta\AdminBundle\Controller;


use Gta\CoreBundle\Controller\CoreController;
use Gta\CoreBundle\Exception\UncategorizedGtaException;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use SensioLabs\AnsiConverter\AnsiToHtmlConverter;
use SensioLabs\AnsiConverter\Theme\SolarizedXTermTheme;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Output\BufferedOutput;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Finder\Finder;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\KernelInterface;
use Symfony\Component\HttpKernel\Profiler\Profiler;
use Symfony\Component\Serializer\Encoder\DecoderInterface;

/**
 * Class ProfilingLController
 *
 * @package Gta\AdminBundle\Controller
 * @author  Seif <ben.s@mipih.fr> (05/08/2019/ 16:58)
 * @version 19
 * @Route("/profile")
 */
class ProfilingController extends CoreController
{
    /**
     * @Route("/list")
     *
     * @return \Symfony\Component\HttpFoundation\Response
     * @author Seif <ben.s@mipih.fr>
     */
    public function indexAction()
    {
        return $this->render('::homepage.html.twig');
    }

    /**
     * @Route("/url/{count}/{token}",name="profile_url")
     * @param \Symfony\Component\HttpKernel\Profiler\Profiler $profiler
     *
     * @param int                                             $count
     *
     * @param null                                            $token
     *
     * @return \Symfony\Component\HttpFoundation\Response
     * @author Seif <ben.s@mipih.fr>
     */
    public function urlAction(Profiler $profiler, $count = 100, $token = null)
    {
        if (!$this->has('profiler')) {
            return new Response('You should not be here', 404);
        }
        $dataProfiler = $profiler->find('', '', $count, '', '', '');
        if (!$dataProfiler) {
            return new Response('Profiler did not find data');
        }
        $data = [];
        foreach ($dataProfiler as $row) {

            $currentToken = $row['token'];
            if (!$currentToken) {
                continue;
            }
            // si un token a été renseigné dans la route, on compare avec le token en cours
            if ((null !== $token) && ($currentToken !== $token)) {
                continue;

            }

            $profile = $profiler->loadProfile($currentToken);
            /** @var \Symfony\Component\HttpKernel\DataCollector\TimeDataCollector $timeCollector */
            $timeCollector = $profile->getCollector('time');
            /** @var \Symfony\Component\HttpKernel\DataCollector\ConfigDataCollector $configCollector */
            $configCollector = $profile->getCollector('config');
            /** @var \Doctrine\Bundle\DoctrineBundle\DataCollector\DoctrineDataCollector $dbCollector */
            $dbCollector = $profile->getCollector('db');
            /** @var \Symfony\Component\HttpKernel\DataCollector\MemoryDataCollector $memoryCollector */
            $memoryCollector = $profile->getCollector('memory');
            /*
            foreach ($profile->getCollectors() as $k => $v) {
                var_dump($k);
            }
            */
            // construction variables
            $duration = $timeCollector->getDuration();
            $url = $profile->getUrl();

            // Elmiminer les url du profileur
//            if (false !== strpos($url, '/api/admin/profile')
//                || $request->getSchemeAndHttpHost().'/' === $url) {
//                continue;
//            }
            $data [$currentToken] = [
                'token'        => $currentToken,
                'url'          => $url,
                'status_code'  => $profile->getStatusCode(),
                'date'         => $profile->getTime(),
                'duration'     => $duration,
                'expensive'    => ($duration >= 6000) ? 1 : 0,
                'query_count'  => $dbCollector->getQueryCount(),
                'query_time'   => $dbCollector->getTime(),
                'env'          => $configCollector->getEnv(),
                'init_time'    => $timeCollector->getInitTime(),
                'memory_usage' => $memoryCollector->getMemory(),
                'memory_limit' => $memoryCollector->getMemoryLimit(),
            ];
        }

        return $this->render('profiling/profile_url.html.twig', ['data' => $data]);
    }

    /**
     * @return \Symfony\Component\HttpFoundation\Response
     * @author Seif <ben.s@mipih.fr>
     * @Route("/sql_logs_list",name="sql_logs_list")
     */
    public function sqlLogsListAction()
    {

        $logDir = $this->getParameter('kernel.logs_dir');

        $finder = new Finder();
        $finder->files()->in($logDir)->name('doctrine_*.log')->sortByName();
        $files = iterator_to_array($finder->getIterator());

        return $this->render('profiling/profile_sql_list.html.twig', ['files' => $files]);
    }

    /**
     * @param \Symfony\Component\Serializer\Encoder\DecoderInterface $decoder
     *
     * @param \Symfony\Component\HttpFoundation\Request              $request
     *
     * @param null                                                   $file
     *
     * @param null                                                   $token
     *
     * @return \Symfony\Component\HttpFoundation\Response
     * @author Seif <ben.s@mipih.fr>
     * @Route("/sql/{file}/{token}",name="profile_sql")
     */
    public function sqlAction(
        DecoderInterface $decoder,
        Request $request,
        $file = null,
        $token = null
    ) {
        $logDir = $this->getParameter('kernel.logs_dir');
        // si un fichier a été spécifié dans la route
        if (null === $file) {
            // par défaut on essayera de charger le fichier du jour
            $env = $this->getParameter('kernel.environment');
            $date = date('Y-m-d');
            if ($request->query->has('date')) {
                $date = $request->query->get('date');
            }
            $completeFileName = $logDir.'/doctrine_'.$env.'-'.$date.'.log';
        } else {
            $completeFileName = $logDir.'/'.$file;
        }
        // tester existence fichier
        if (!file_exists($completeFileName)) {
            return new Response('File <i> '.$completeFileName.' </i> is missing', 500);
        }
        // ouverture et parcours du fichier de log
        $handle = fopen($completeFileName, 'r');
        $content = [];
        if ($handle) {
            while (($line = fgets($handle)) !== false) {
                if (empty($line)) {
                    continue;
                }
                $contentRow = $decoder->decode($line, 'json', ['json_decode_associative' => JSON_OBJECT_AS_ARRAY]);
                // si un token a été spécifié dans la route
                if (null !== $token && $token !== $contentRow['token']) {
                    continue;
                }
                $content [] = $contentRow;
            }

            fclose($handle);
        }

        return $this->render(':profiling:profile_sql.html.twig', ['queries' => $content]);
    }

    /**
     *
     * @param \Symfony\Component\HttpKernel\KernelInterface $kernel
     *
     * @param \Symfony\Component\Filesystem\Filesystem      $fs
     * @param int                                           $hard
     *
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \Exception
     * @author Seif <ben.s@mipih.fr>
     * @Route("/clear_cache/{hard}",name="clear_cache")
     */
    public function clearCacheAction(KernelInterface $kernel, Filesystem $fs, $hard = 0)
    {
        if (1 === intval($hard)) {
            $fs->remove($folder = $kernel->getCacheDir());

            return new Response('Cache folder <i> '.$folder.' </i> was successfully cleared');
        }

        $application = new Application($kernel);
        $application->setAutoExit(false);
        $input = new ArrayInput(
            [
                'command' => 'cache:clear',
            ]
        );
        $output = new BufferedOutput();
        $application->run($input, $output);

        $content = $output->fetch();
        $converter = new AnsiToHtmlConverter(new SolarizedXTermTheme(), true);

        return $this->render('profiling/profile_command_output.html.twig', ['output' => $converter->convert($content)]);
    }

    /**
     * @param \Symfony\Component\HttpKernel\KernelInterface $kernel
     * @param \Symfony\Component\Filesystem\Filesystem      $fs
     *
     * @author Seif <ben.s@mipih.fr>
     * @Route("/clear_profiles",name="clear_profiles")
     */
    public function clearProfilingDataAction(KernelInterface $kernel, Filesystem $fs)
    {
        $profilesDir = $kernel->getCacheDir().'/profiler';
        $fs->remove($profilesDir);
        die('Profiles folder { '.$profilesDir.' } was successfully cleared');
    }

    /**
     * @return \Symfony\Component\HttpFoundation\Response
     * @author Seif <ben.s@mipih.fr>
     * @Route("/view_logs",name="view_logs")
     */
    public function viewLogsAction()
    {
        $logDir = $this->getParameter('kernel.logs_dir');
        $finder = new Finder();
        $finder->files()->in($logDir)->name('*.log')->sortByName();
        $files = iterator_to_array($finder->getIterator());

        return $this->render('profiling/profile_logs_list.html.twig', ['files' => $files]);
    }

    /**
     * @param                                                        $file
     *
     * @param \Symfony\Component\Serializer\Encoder\DecoderInterface $decoder
     *
     * @return \Symfony\Component\HttpFoundation\Response
     * @author Seif <ben.s@mipih.fr>
     * @Route("/view_log_file_content/{file}",name="view_log_file_content")
     */
    public function viewLogFileContentAction($file, DecoderInterface $decoder)
    {
        $file = $this->getParameter('kernel.logs_dir').'/'.$file;
        $parse = function ($content) {
            if (!is_string($content) || strlen($content) === 0) {
                return array();
            }


            preg_match(
                '/\[(?P<date>.*)\] (?P<logger>\w+).(?P<level>\w+): (?P<message>.*[^ ]+) (?P<context>[^ ]+) (?P<extra>[^ ]+)/',
                $content,
                $data
            );

            if (!isset($data['date'])) {
                return array();
            }

            return array(
                'date'    => \DateTime::createFromFormat('Y-m-d H:i:s', $data['date']),
                'logger'  => $data['logger'],
                'level'   => $data['level'],
                'message' => $data['message'],
                'context' => $data['context'],
                'extra'   => $data['extra'],
            );
        };
        /* Read and parse, Yeaaah */
        $handle = fopen($file, 'r');


        $content = [];
        if ($handle) {
            while (($line = fgets($handle)) !== false) {
                if (empty($line)) {
                    continue;
                }
                $content [] = $parse($line);
            }

            fclose($handle);
        }

        return $this->render(
            'profiling/profile_view_file_content.html.twig',
            ['file_content' => $content]
        );
    }

    /**
     *
     * @return \Symfony\Component\HttpFoundation\Response
     * @author Seif <ben.s@mipih.fr>
     * @Route("/php_ini",name="php_ini")
     */
    public function showPhpIniFileAction()
    {
        return new Response('<pre>'.file_get_contents(php_ini_loaded_file()).'</pre>');
    }

    /**
     * @param \Symfony\Component\HttpKernel\KernelInterface $kernel
     *
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \Exception
     * @author Seif <ben.s@mipih.fr>
     * @Route("/metrics_generate",name="metrics_generate")
     */
    public function generatePhpMetricsAction(KernelInterface $kernel)
    {
        $application = new Application($kernel);
        $application->setAutoExit(false);
        $input = new ArrayInput(
            [
                'command' => 'metrics:generate',
            ]
        );
        $input->setInteractive(false);

        $output = new BufferedOutput();
        $application->run($input, $output);

        $content = $output->fetch();
        $converter = new AnsiToHtmlConverter(new SolarizedXTermTheme(), true);

        return $this->render('profiling/profile_command_output.html.twig', ['output' => $converter->convert($content)]);
    }

    /**
     * @Route("/debug_container",name="debug_container")
     * @param \Symfony\Component\HttpKernel\KernelInterface $kernel
     * @param \Symfony\Component\HttpFoundation\Request     $request
     *
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \Exception
     * @author Seif <ben.s@mipih.fr>
     */
    public function debugContainerAction(KernelInterface $kernel, Request $request)
    {
//        $command = new ContainerDebugCommand();
//        $help = $command->getHelp();
//        $options = $command->getDefinition()->getOptions();
        $term = null;
//        if ($request->query->has('t')) {
//            $term = $request->query->get('t');
//        }
        $application = new Application($kernel);
        $application->setAutoExit(false);
        $input = new ArrayInput(
            [
                'command'        => 'debug:container',
                '--show-private' => true,
            ]
        );
        $output = new BufferedOutput();
        $application->run($input, $output);

        $content = $output->fetch();
        $converter = new AnsiToHtmlConverter(new SolarizedXTermTheme(), true);

        return $this->render(
            'profiling/profile_command_output.html.twig',
            [
//                'help' => $converter->convert($help),
//                'options'   => $options,
                'output' => $converter->convert($content),
            ]
        );
    }

    /**
     * @Route("/debug_router",name="debug_router")
     * @param \Symfony\Component\HttpKernel\KernelInterface $kernel
     * @param \Symfony\Component\HttpFoundation\Request     $request
     *
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \Exception
     * @author Seif <ben.s@mipih.fr>
     */
    public function debugRouterAction(KernelInterface $kernel, Request $request)
    {
        $term = null;
        if ($request->query->has('t')) {
            $term = $request->query->get('t');
        }
        $application = new Application($kernel);
        $application->setAutoExit(false);
        $input = new ArrayInput(
            [
                'command'            => 'debug:router',
                '--show-controllers' => true,
            ]
        );
        $output = new BufferedOutput();
        $application->run($input, $output);

        $content = $output->fetch();
        $converter = new AnsiToHtmlConverter(new SolarizedXTermTheme(), true);

        return $this->render('profiling/profile_command_output.html.twig', ['output' => $converter->convert($content)]);
    }

    /**
     * @Route("/debug_event_dispatcher",name="debug_event_dispatcher")
     * @param \Symfony\Component\HttpKernel\KernelInterface $kernel
     * @param \Symfony\Component\HttpFoundation\Request     $request
     *
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \Exception
     * @author Seif <ben.s@mipih.fr>
     */
    public function debugEventDispatcherAction(KernelInterface $kernel, Request $request)
    {
        $term = null;
        if ($request->query->has('t')) {
            $term = $request->query->get('t');
        }
        $application = new Application($kernel);
        $application->setAutoExit(false);
        $input = new ArrayInput(
            [
                'command' => 'debug:event-dispatcher',
            ]
        );
        $output = new BufferedOutput();
        $application->run($input, $output);

        $content = $output->fetch();
        $converter = new AnsiToHtmlConverter(new SolarizedXTermTheme(), true);

        return $this->render('profiling/profile_command_output.html.twig', ['output' => $converter->convert($content)]);
    }
}