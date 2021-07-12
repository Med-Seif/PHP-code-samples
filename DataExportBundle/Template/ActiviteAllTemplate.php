<?php
/**
 * User Seif <ben.s@mipih.fr>
 * Date time: 23/05/2019 11:51
 */

namespace Gta\DataExportBundle\Template;

use Gta\DataExportBundle\Template\Helper\DimensionHelper;

/**
 * Class ActiviteTemplate
 *
 * @package Gta\DataExportBundle\Template
 * @author  Seif <ben.s@mipih.fr> (23/05/2019/ 13:44)
 * @version 19
 */
class ActiviteAllTemplate extends SimpleTableTemplate
{
    /**
     * @param mixed $data
     *
     * @return $this|\Gta\DataExportBundle\Template\SimpleTableTemplate
     * @throws \Gta\DataExportBundle\Exception\InvalidInputExportData
     * @throws \PhpOffice\PhpSpreadsheet\Exception
     * @author Seif <ben.s@mipih.fr>
     */
    public function generateFile($data)
    {


        $generalKey = 'general';
        $medicalKey = 'medical';
        $dimensionHelperGeneral = new DimensionHelper($data[$generalKey], $this->colTitles[0]);
        $dimensionHelperMedical = new DimensionHelper($data[$medicalKey], $this->colTitles[1]);
        $this->createTableActivite($data[$generalKey], $dimensionHelperGeneral, $this->colTitles[0], false);
        $this->createTableActivite($data[$medicalKey], $dimensionHelperMedical, $this->colTitles[1], true);
        $index = 0;
        if (isset($data['type'])) {
            $index = ($generalKey === $data['type']) ? 0 : 1;
        }

        $this->exportAdapter->setActiveSheet($index);
        $this->setScreenTitle('Liste des activités');
        return $this;
    }

    /**
     * Crétaion d'un seul tableau
     *
     * @param                                                       $data
     *
     * @param \Gta\DataExportBundle\Template\Helper\DimensionHelper $dimensionHelper
     * @param                                                       $titles
     * @param bool                                                  $newSheet
     *
     * @throws \Gta\DataExportBundle\Exception\InvalidInputExportData
     * @throws \PhpOffice\PhpSpreadsheet\Exception
     * @author Seif <ben.s@mipih.fr>
     */
    protected function createTableActivite($data, DimensionHelper $dimensionHelper, $titles, $newSheet = false)
    {
        // tableau principal
        $this->createTable(
            $data,
            $dimensionHelper,
            $titles,
            true,
            $newSheet
        );
        $this->defineCommonOptions($dimensionHelper);
    }
}
