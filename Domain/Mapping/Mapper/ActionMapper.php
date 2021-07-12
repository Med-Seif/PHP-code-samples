<?php
/**
 * Created by PhpStorm.
 * User: O Lamrid
 * Date: 18/03/2019
 * Time: 16:49
 */

namespace Gta\Domain\Mapping\Mapper;

/**
 * Class ActionMapper
 *
 * @package Gta\CoreBundle\Mapper
 * @author  Seif <ben.s@mipih.fr> (18/04/2019/ 20:15)
 * @version 19
 */
class ActionMapper extends AbstractMapper
{
    /**
     * @param array $data
     * @param array $extra
     *
     * @return array
     * @author Seif <ben.s@mipih.fr>
     */
    public function mapData(array $data, array $extra = [])
    {
        $mapped = [];

        foreach ($data as $key => $row) {
            $row['service'] = [
                'typtab' => $row['typtab'],
                'servic' => $row['servic'],
                'sertyp' => $row['sertyp'],
                'serlib' => $row['serlib'],
            ];
            $row['periode'] = [
                'datdeb' => $row['datdeb'],
                'datfin' => $row['datfin'],
            ];
//            $oDateff = date_create_from_format('d/m/Y H:i:s', $row['dateff']);
//            $row['dateff'] = $oDateff->format('Y/m/d H:i:s');

            $mapped[$key] = $this->removeKeys($row, [
                'servic', 'serlib','sertyp','typtab','datdeb','datfin','codact','codfct'
            ]);
        }

        return $mapped;
    }
}