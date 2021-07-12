<?php
/**
 * This file is part of GTA api project.
 * (c) Mansour BERREKIA <berrekia.m@mipih.fr>
 * @author mberrekia <berrekia.m@mipih.fr>
 * Date 30/01/2020 on  12:14
 */

namespace Gta\DataExportBundle\Planning\Formatter\Intervenant;


/**
 * Class HeaderBrokenIntervenant
 * @package Gta\DataExportBundle\Planning\Formatter\Intervenant
 */
class HeaderBrokenIntervenant extends  AbstractIntervenant
{

    public function format($row, $col, $data, $extraData = [])
    {
        $this->getAdapter()->writeString(
            $row,
            $col,
            $data['nom']."\n".$data['prenom']."\n".$data['ppt']."\n".$data['matric']
        );

        return $col;
    }
}