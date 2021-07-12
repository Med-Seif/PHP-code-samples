<?php
/**
 * User Seif <ben.s@mipih.fr>
 * Date time: 29/04/2019 11:16
 */

namespace Gta\Domain\Mapping\Mapper;

use Gta\Domain\Lib\Std;
use Gta\MedicalBundle\Utils\Lib\MedicalUtils;

/**
 * Class DeplacementAndExpMapper
 *
 * @package Gta\MedicalBundle\Mapper
 * @author  Seif <ben.s@mipih.fr> (29/04/2019/ 11:17)
 * @version 19
 */
class DeplacementAndExpMapper extends AbstractMapper
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
        // split deplacement du deplacement exce
        $xdep = $this->getDeplacementEx($data['dep']);
        $dep = $this->getDeplacement($data['dep']);

        $mappedDep = $this->mapDep($data, $dep);
        $mappedXdep = $this->mapXdep($data, $xdep);

        $merged = array_merge($mappedDep, array_values($mappedXdep));
        
        $matrics  = array_column($merged, 'matric');
        $dateffs = array_column($merged, 'dateff');
        $typtabs  = array_column($merged, 'typtab');
        $servics = array_column($merged, 'servic');
        $sertyps = array_column($merged, 'sertyp');
        $hors = array_column($merged, 'hor');
        
        $success = array_multisort($matrics, SORT_ASC,
            $dateffs, SORT_ASC,
            $typtabs, SORT_ASC,
            $servics, SORT_ASC,
            $sertyps, SORT_ASC,
            $hors, SORT_ASC,
            $merged);
        
        return $merged;
    }

    /**
     * @param $data
     * @param $dep
     *
     * @return array
     */
    private function mapDep($data, $dep)
    {
        $mappedDep = [];
        // ajout des déplacement pour les astreintes
        foreach ($data['ast'] as $row) {
            $durees = []; // tableau qui contiendra les -3 et +3, nb passage par reférence
            $mappedRow = [
                'dep' => $this->getDeplacementByAstreinte($dep, $row, $durees),
                'plus3h' => $durees['+3h'],
                'moins3h' => $durees['-3h'],
            ];
            $mappedRow = $this->populateCommonData($mappedRow, $row, $data);
            $mappedDep[] = array_merge($row, $mappedRow);
        }
        return $mappedDep;
    }

    /**
     * @param $data
     * @param $xdep
     *
     * @return array
     */
    private function mapXdep($data, $xdep)
    {
        $mappedXdep = [];
        // format les deplacement excep comme le tableau ci dessus
        foreach ($xdep as $row) {
            $dureesExp = [];
            $uniqueKey = implode(':', [$row['dateff'], $row['matric'], $row['hor']]);
            if (array_key_exists($uniqueKey, $mappedXdep)) {
                continue;
            }
            $mappedRow = [
                'matric' => $row['matric'],
                'dateff' => $row['dateff'],
                'hor'    => $row['hor'],
                'horlib' => $row['horlib'],
                'plsact' => 'xdep',
                'actlib' => 'Dépl. exceptionnel',
                'dep'    => $this->groupDeplacementEx($xdep, $data['tuf'], $row, $dureesExp),
                'plus3h'    => $dureesExp['+3h'],
                'moins3h'    => $dureesExp['-3h'],
                'typtab' => $row['typtab'],
                'servic' => $row['servic'],
                'sertyp' => $row['sertyp'],
            ];
            $mappedXdep[$uniqueKey] = $this->populateCommonData($mappedRow, $row, $data);
        }

        return $mappedXdep;
    }

    /**
     * @param $mappedRow
     * @param $row
     *
     * @param $data
     *
     * @return mixed
     * @author Seif <ben.s@mipih.fr>
     */
    private function populateCommonData(array $mappedRow, array $row, array $data)
    {
        $valorisation = $this->getValorisationByMatric($data['valo'], $row['matric']);
        $getValoColor = function ($matric) use ($valorisation) {
            if (!MedicalUtils::intervIsRE($matric)) {
                return (true === $valorisation) ? 'green' : 'red';
            }

            return '';
        };
        $mappedRow['service'] = [
            'typtab' => $row['typtab'],
            'servic' => $row['servic'],
            'sertyp' => $row['sertyp'],
            'serlib' => $row['serlib']
        ];
        $mappedRow['jour'] = $row['jour'];
        $mappedRow['dateff'] = $row['dateff'].($row['tcltypj'] ? ' ('.$row['tcltypj'].')' : '');
        $mappedRow['name'] = $this->getNameByMatric($data['int'], $row['matric']);
        $mappedRow['valo'] = $valorisation;
        $mappedRow['etat'] = $this->getEtatService($data['serv'], $row );
        $mappedRow['valo_color'] = $getValoColor($row['matric']);

        return $mappedRow;
    }

    /**
     * @param $data
     * @param $matric
     *
     * @return string|null
     */
    private function getNameByMatric($data, $matric)
    {
        if (isset($data[$matric])) {
            return $data[$matric];
        }

        return null;
    }

    /**
     * @param $dep
     *
     * @return array
     */
    private function getDeplacement($dep)
    {
        return array_filter(
            $dep,
            function ($d) {
                return $d['deptyp'] == 'dep';
            }
        );
    }

    /**
     * Vérifier la date d'effet sur les périodes gmesec
     *
     * @param $data
     * @param $row
     *
     * @return string
     */
    private function getEtatService($data, array $row)
    {
        $dateff = Std::convertDateEn($row['dateff']);
        $code = $row['servic'].'/'.$row['sertyp'];

        foreach ($data as $value) {
            $deb = Std::convertDateEn($value['datdeb'], 'd/m/Y h:i:s');
            $fin = Std::convertDateEn($value['datfin'], 'd/m/Y h:i:s');
            if (($dateff >= $deb && $dateff <= $fin) && ($value['code'] == $code)) {
                return $value['etat'];
            }
        }

        return '';

    }

    /**
     * @param $data
     * @param $matric
     *
     * @return bool
     */
    private function getValorisationByMatric($data, $matric)
    {
        foreach ($data as $value) {
            if (trim($value['matric']) == trim($matric)) {
                return true;
            }
        }

        return false;
    }

    /**
     * @param $data
     * @param $tuf
     * @param $xdep
     * @param $durees
     *
     * @return array
     */
    private function groupDeplacementEx($data, $tuf, $xdep, & $durees)
    {
        $mapped = [];
        $durees = [
            '-3h' => 0,
            '+3h' => 0,
        ];
        foreach ($data as $row) {
            if ($row['matric'] == $xdep['matric']
                && $row['dateff'] == $xdep['dateff']
                && $row['hor'] == $xdep['hor']
                && $row['typtab'] == $xdep['typtab']
                && $row['sertyp'] == $xdep['sertyp']
                && $row['servic'] == $xdep['servic']
            ) {
                $this->calc3hDuree($row['dur'], $durees);
                $mapped[] = [
                    'hdeb'   => $row['hdeb'],
                    'hfin'   => $row['hfin'],
                    'dur'    => $row['dur'],
                    'uf'     => $row['uf'],
                    'uflib'  => $this->getUfLabel($tuf, $row['uf']),
                    'remu'   => $row['remu'],
                    'depcom' => $row['depcom'],
                    'deptyp' => $row['deptyp'],
                ];
            }
        }

        return $mapped;
    }


    /**
     * @param       $data
     * @param       $ast
     * @param array $durees
     *
     * @return array
     */
    private function getDeplacementByAstreinte($data, $ast, & $durees)
    {
        $mapped = [];
        $durees = [
            '-3h' => 0,
            '+3h' => 0,
        ];
        foreach ($data as $dep) {
            if ($dep['matric'] == $ast['matric']
                && $dep['dateff'] == $ast['dateff']
                && $dep['hor'] == $ast['hor']
                && $dep['typtab'] == $ast['typtab']
                && $dep['sertyp'] == $ast['sertyp']
                && $dep['servic'] == $ast['servic']
            ) {
                $this->calc3hDuree($dep['dur'], $durees);
                $mapped[] = [
                    'hdeb'   => $dep['hdeb'],
                    'hfin'   => $dep['hfin'],
                    'dur'    => $dep['dur'],
                    'uf'     => $dep['uf'],
                    'uflib'  => '',
                    'remu'   => $dep['remu'],
                    'depcom' => $dep['depcom'],
                    'deptyp' => $dep['deptyp'],
                ];
            }
        }

        return $mapped;
    }

    /**
     * @param $dureeInHours
     * @param $durees
     *
     * @author Seif <ben.s@mipih.fr>
     */
    private function calc3hDuree($dureeInHours, & $durees)
    {

        if (false === $dureeMinutes = Std::hourToMinutes($dureeInHours)) {
            return;
        }
        $signe3h = ($dureeMinutes >= 180) ? '+' : '-';
        $durees [$signe3h.'3h']++;
    }

    /**
     * @param $dep
     *
     * @return array
     */
    private function getDeplacementEx($dep)
    {
        return array_filter(
            $dep,
            function ($d) {
                return $d['deptyp'] == 'xdep';
            }
        );
    }

    /**
     * @param $data
     * @param $uf
     *
     * @return string
     */
    private function getUfLabel($data, $uf = null)
    {
        if (null === $uf || !isset($data[$uf])) {
            return '';
        }

        return $data[$uf];
    }
}