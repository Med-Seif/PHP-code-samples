<?php
/**
 * User Seif <ben.s@mipih.fr>
 * Date time: 26/02/2020 14:17
 */

namespace Gta\DataExportBundle\Event\Listener;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\Encoder\DecoderInterface;

/**
 * Class TsExportDataSourceHttpResponseStrategy
 *
 * @package Gta\DataExportBundle\Event\Listener
 * @author  Seif <ben.s@mipih.fr> (26/02/2020/ 14:18)
 * @version 19
 */
class TsExportDataSourceHttpResponseStrategy implements TsExportDataSourceStrategyInterface
{
    /**
     * @var \Symfony\Component\Serializer\Encoder\DecoderInterface
     */
    private $decoder;

    /**
     * TsExportDataSourceHttpResponseStrategy constructor.
     *
     * @param \Symfony\Component\Serializer\Encoder\DecoderInterface $decoder
     */
    public function __construct(DecoderInterface $decoder)
    {
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
        return $this->decoder->decode($response->getContent(), 'json');
    }
}