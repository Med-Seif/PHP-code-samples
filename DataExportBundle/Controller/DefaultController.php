<?php

namespace Gta\DataExportBundle\Controller;

use Gta\DataExportBundle\Adapters\ExportAdapterInterface;
use Gta\DataExportBundle\Annotation\ExportAnnotation as Export;
use PhpOffice\PhpSpreadsheet\RichText\RichText;
use PhpOffice\PhpSpreadsheet\Style\Color;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    /**
     * @Route ("/export", name="test_export")
     * @return \Symfony\Component\HttpFoundation\BinaryFileResponse
     * @throws \PhpOffice\PhpSpreadsheet\Exception
     * @throws \PhpOffice\PhpSpreadsheet\Writer\Exception
     * @author Seif <ben.s@mipih.fr>
     */
    public function testexportAction()
    {
        $colors = [
            1 => '000000', //  System Colour #1 - Black
            2 => 'FFFFFF', //  System Colour #2 - White
            3 => 'E40000', //  System Colour #3 - Red
            4 => '008500', //  System Colour #4 - Green
            5 => '0021FF', //  System Colour #5 - Blue
            6 => 'FFFF00', //  System Colour #6 - Yellow
            7 => 'FF00FF', //  System Colour #7- Magenta
            8 => 'FF9200', //  System Colour #8- Cyan
        ];
        $bold = [true, false];
        /**
         * \Gta\DataExportBundle\Utils\Export\Adapters\AbstractExportAdapter
         */
        $defaultAdapter = $this->get('gta_data_export.xlsx_export_adapter');
        $defaultAdapter->createNewStyleObject();

        $defaultAdapter
            ->fontBold()
            ->fontItalic()
            ->fontUnderline()
            ->fontColor('808080')
            ->border(ExportAdapterInterface::ALIGN_BOTTOM, 1, 'FF000000')
            ->border(ExportAdapterInterface::ALIGN_TOP, 1, 'FF000000');
        /*
                $style = [
                    'fill'        => [
                        'fillType' => Fill::FILL_SOLID,
                        'color'    => [
                            'argb' => '',
                        ],
                    ],
                    'font'        => [
                        'name'          => 'Arial',
                        'bold'          => true,
                        'italic'        => false,
                        'underline'     => Font::UNDERLINE_DOUBLE,
                        'strikethrough' => false,
                        'color'         => [
                            'argb' => '808080',
                        ],
                    ],
                    'borders'     => [
                        'bottom' => [
                            'borderStyle' => Border::BORDER_DASHDOT,
                            'color'       => [
                                'rgb' => '808080',
                            ],
                        ],
                        'top'    => [
                            'borderStyle' => Border::BORDER_DASHDOT,
                            'color'       => [
                                'rgb' => '808080',
                            ],
                        ],
                    ],
                    'quotePrefix' => true,
                ];
        */
        $i = 1;
        $richText = new RichText();
        $richText->createTextRun('Benna')->getFont()->setColor(new Color(Color::COLOR_GREEN));
        $richText->createTextRun(' ya7za9')->getFont()->setColor(new Color(Color::COLOR_GREEN));

        $richText2 = new RichText();
        $richText2->createTextRun('Benna')->getFont()->setColor(new Color(Color::COLOR_RED));
        $richText2->createTextRun(' ya7za9')->getFont()->setColor(new Color(Color::COLOR_RED));

        $richText3 = new RichText();
        $richText3->createTextRun('Benna')->getFont()->setColor(new Color(Color::COLOR_BLUE));
        $richText3->createTextRun(' ya7za9')->getFont()->setColor(new Color(Color::COLOR_BLUE));
        while ($i < 100) {
            $defaultAdapter->setDefaultWidth('20');
            $j = 1;

            while ($j < 20) {
//                $defaultAdapter->bgColor($colors[rand(1, 8)]);
//                $defaultAdapter->fontColor($colors[rand(1, 8)]);
                if ($i == 1) {
                    if ($j === 1) {
                        $defaultAdapter->writeString($i, $j, $richText3, $defaultAdapter->getStyleObject());
                    } else {
                        $defaultAdapter->writeString($i, $j, $richText2, $defaultAdapter->getStyleObject());
                    }
                } else {
                    if ($j === 1) {
                        $defaultAdapter->writeString($i, $j, $richText3, $defaultAdapter->getStyleObject());
                    } else {
                        $defaultAdapter->writeString($i, $j, $richText, $defaultAdapter->getStyleObject());
                    }
                }
                $j++;
            }
            $i++;
        }
        $defaultAdapter->setRepeatRow(1, 1);
        $defaultAdapter->setRepeatCol('A', 'A');
//        $arrayData = [
//            [null, 2010, 2011, 2012],
//            ['Q1', 12, 15, 21],
//            ['Q2', 56, 73, 86],
//            ['Q3', 52, 61, 69],
//            ['Q4', 30, 32, 0],
//            [null, 2010, 2011, 2012],
//            ['Q1', 12, 15, 21],
//            ['Q2', 56, 73, 86],
//            ['Q3', 52, 61, 69],
//            ['Q4', 30, 32, 0],
//            [null, 2010, 2011, 2012],
//            ['Q1', 12, 15, 21],
//            ['Q2', 56, 73, 86],
//            ['Q3', 52, 61, 69],
//            ['Q4', 30, 32, 0],
//        ];
//        $defaultAdapter->addSheet();
//        $defaultAdapter->fromArray($arrayData);

        $defaultAdapter->setFilename('seif')->writeToFile();


        return $this->file($defaultAdapter->getFilename()); // le helper file de symfony se charge de TOUT!
    }

    /**
     * @Export(
     *     templateClassName = "Gta\DataExportBundle\Template\SimpleTableTemplate",
     *     styleFileName = "default_table.yml"
     * )
     *
     * @Route("/export_1")
     * @author Seif <ben.s@mipih.fr>
     */
    public function exportWithEventAction()
    {
        $x = function () {
            return rand(10000, 30000);
        };
        $i = 0;
        $data = [];
        while ($i < 40) {
            $j = 0;
            $row = null;
            while ($j < 20) {
                if ($j < 10) {
                    $row[] = $x();
                } else {
                    $row[] = $this->generateRandomString(4);
                }

                $j++;
            }
            $data[] = $row;
            $i++;
        }

        return $this->json(
            $data
        );
    }

    /**
     * @Export(
     *     templateClassName = "Gta\DataExportBundle\Template\SimpleTableTemplate",
     *     styleFileName = "default_table.yml"
     * )
     *
     * @Route("/export_2")
     * @author Seif <ben.s@mipih.fr>
     */
    public function exportCodif()
    {
        $x = function () {
            return rand(10000, 30000);
        };
        $i = 0;
        $data = [];
        while ($i < 40) {
            $j = 0;
            $row = null;
            while ($j < 20) {
                if ($j < 10) {
                    $row[] = $x();
                } else {
                    $row[] = $this->generateRandomString(4);
                }

                $j++;
            }
            $data[] = $row;
            $i++;
        }
        $codif = [['lorem', 'ipsur'], ['dolor', 'amet'], ['absurdum ', 'cultuque ']];

        return $this->json(
            [
                'results' => $data,
                'extra'   => [
                    'codif' => $codif,
                ],
            ]
        );
    }

    /**
     * @Route("/export_test_all",name="export_test_all")
     * @author Seif <ben.s@mipih.fr>
     */
    public function testAllExportAction()
    {
        return $this->render('export.html.twig');
    }

    private function generateRandomString($length = 10)
    {
        $characters = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }

        return $randomString;
    }
}
