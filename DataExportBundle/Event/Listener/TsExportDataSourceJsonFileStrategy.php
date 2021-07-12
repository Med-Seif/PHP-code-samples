<?php
/**
 * User Seif <ben.s@mipih.fr>
 * Date time: 26/02/2020 14:16
 */

namespace Gta\DataExportBundle\Event\Listener;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Kernel;
use Symfony\Component\HttpKernel\KernelInterface;
use Symfony\Component\Serializer\Encoder\DecoderInterface;

/**
 * Class TsExportDataSourceJsonFileStrategy
 *
 * @package Gta\DataExportBundle\Event\Listener
 * @author  Seif <ben.s@mipih.fr> (26/02/2020/ 14:18)
 * @version 19
 */
class TsExportDataSourceJsonFileStrategy implements TsExportDataSourceStrategyInterface
{
    const FILE_NAME = 'data-list2.json';
//    const FILE_NAME = 'data-list-light.json';
    /**
     * @var string
     */
    private $filePath;
    /**
     * @var \Symfony\Component\Serializer\Encoder\DecoderInterface
     */
    private $decoder;

    /**
     * TsExportDataSourceJsonFileStrategy constructor.
     *
     * @param \Symfony\Component\HttpKernel\KernelInterface          $kernel
     * @param \Symfony\Component\Serializer\Encoder\DecoderInterface $decoder
     */
    public function __construct(KernelInterface $kernel, DecoderInterface $decoder)
    {
        $this->filePath = $kernel->getRootDir().'/../tests/DataStores/'.self::FILE_NAME;
        $this->decoder = $decoder;
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Response|null $response
     *
     * @return array
     * @author Seif <ben.s@mipih.fr>
     */
    public function loadData(Response $response = null): array
    {
        $content = file_get_contents($this->filePath);

        return $this->decoder->decode($content, 'json');
    }
}