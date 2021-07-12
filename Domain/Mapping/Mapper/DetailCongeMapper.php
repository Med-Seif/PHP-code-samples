<?php
/**
 * Created by PhpStorm.
 * User: bouchind.f
 * Date: 30/04/2019
 * Time: 09:26
 */

namespace Gta\Domain\Mapping\Mapper;

use Gta\CoreBundle\Mapper\Look\PeriodeLook;
use Gta\CoreBundle\Mapper\MapperInterface;
use Gta\MedicalBundle\Mapper\Look\ServiceLook;

class DetailCongeMapper extends AbstractMapper
{
    public function mapData(array $data, array $extra = [])
    {
        $mapped = ['total' => ['total_jours' => 0, 'total_heures' => 0]];
        $mapped['detail'] = [];
        foreach ($data as $periodeConge) {
            $periodeConge[PeriodeLook::KEY] = PeriodeLook::transform($periodeConge);
            $mapped['detail'][] = $periodeConge;
            $mapped['total']['total_jours'] += $periodeConge['jours'];
            $mapped['total']['total_heures'] += $periodeConge['heures'];
        }
        return $mapped;
    }


}
