<?php
/**
 * User Seif <ben.s@mipih.fr>
 * Date time: 15/03/2019 10:15
 */

namespace Gta\Domain\Mapping\Mapper;

use Gta\Domain\Lib\Std;
use Gta\MedicalBundle\Utils\Lib\MedicalUtils;

/**
 * Class ContratMapper
 *
 * @package Gta\MedicalBundle\Mapper
 * @author  Seif <ben.s@mipih.fr> (15/03/2019/ 16:34)
 * @version 19
 */
class ContratMapper extends AbstractMapper
{
    const TYPTAB = 'typtab';
    const SERVIC = 'servic';
    const SERTYP = 'sertyp';
    const SERLIB = 'serlib';
    const DATDEB = 'datdeb';
    const DATFIN = 'datfin';

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
            $row['valcon'] = Std::minutesToHour($row['valcon']); // conversion secondes en heures
            $row['service'] = [
                self::TYPTAB => $row[self::TYPTAB],
                self::SERVIC => $row[self::SERVIC],
                self::SERTYP => $row[self::SERTYP],
                self::SERLIB => $row[self::SERLIB],
            ];
            $row['periode'] = [
                self::DATDEB => $row[self::DATDEB],
                self::DATFIN => $row[self::DATFIN],
            ];
            $row['datdebSaved'] = $row[self::DATDEB];
            // couleur intervenant
            $row['__attributes'] = [
                'name' => [
                    'color' => MedicalUtils::getColorByMatric($row['matric']),
                ],
            ];
            unset($row[self::DATDEB], $row[self::DATFIN], $row[self::TYPTAB], $row[self::SERVIC], $row[self::SERTYP], $row[self::SERLIB], $row['serviceTri']);
            $mapped[$key] = $row;
        }

        return $mapped;
    }


}