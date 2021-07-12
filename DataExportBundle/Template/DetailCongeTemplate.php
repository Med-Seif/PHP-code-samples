<?php
/**
 * User Seif <ben.s@mipih.fr>
 * Date time: 07/05/2019 13:29
 */

namespace Gta\DataExportBundle\Template;

use Symfony\Component\PropertyAccess\PropertyAccess;

/**
 * Class DetailCongeTemplate
 *
 * @package Gta\DataExportBundle\Template
 * @author  Seif <ben.s@mipih.fr> (07/05/2019/ 13:29)
 * @version 19
 */
class DetailCongeTemplate extends SimpleTableTemplate
{
    /**
     * {@inheritdoc}
     */
    public function generateFile($data)
    {
        $pa = PropertyAccess::createPropertyAccessorBuilder()
            ->enableExceptionOnInvalidIndex()
            ->getPropertyAccessor();
        $data = $pa->getValue($data, '[detail]');

        return parent::generateFile($data);
    }
}