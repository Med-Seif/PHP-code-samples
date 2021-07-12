<?php
/**
 * User Seif <ben.s@mipih.fr>
 * Date time: 08/02/2019 16:18
 */

namespace Gta\DataExportBundle\Annotation;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\ConfigurationAnnotation;

/**
 * Class ExportAnnotation
 *
 * @Annotation
 * @Target({"METHOD"})
 * @package Gta\DataExportBundle\Annotation
 * @author  Seif <ben.s@mipih.fr> (08/02/2019/ 16:44)
 * @version 19
 */
class ExportAnnotation extends ConfigurationAnnotation
{
    /**
     * @var string
     */
    protected $id;
    /**
     * @var string
     */
    protected $templateClassName;
    /**
     * @var string
     */
    protected $styleFileName;

    /**
     * @return string
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param string $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @return string
     */
    public function getTemplateClassName()
    {
        return $this->templateClassName;
    }

    /**
     * @param string $templateClassName
     */
    public function setTemplateClassName($templateClassName)
    {
        $this->templateClassName = $templateClassName;
    }

    /**
     * @return mixed
     */
    public function getStyleFileName()
    {
        return $this->styleFileName;
    }

    /**
     * @param mixed $styleFileName
     */
    public function setStyleFileName($styleFileName)
    {
        $this->styleFileName = $styleFileName;
    }

    /**
     * Returns the alias name for an annotated configuration.
     *
     * @return string
     */
    public function getAliasName()
    {
        return 'Export';
    }

    /**
     * Returns whether multiple annotations of this type are allowed.
     *
     * @return bool
     */
    public function allowArray()
    {
        return true;
    }

}