<?php

namespace Gta\TracabiliteBundle\Entity;

use Gta\TracabiliteBundle\Exception\MissingTracabiliteParameterException;
use Gta\TracabiliteBundle\Resources\StringConstants;
use Symfony\Component\PropertyAccess\PropertyAccess;

/**
 * Objet à passer directement aux formateurs de traçabilité
 *
 * @package Gta\TracabiliteBundle\Entity
 * @author  Seif <ben.s@mipih.fr>
 */
class EgmhistLogObject
{
    /**
     * @var string
     */
    private $codhop;
    /**
     * @var string
     */
    private $nomuti;
    /**
     * @var string
     */
    private $matric;
    /**
     * @var array
     */
    private $ucParams;
    /**
     * @var string
     */
    private $servic;
    /**
     * @var string
     */
    private $sertyp;
    /**
     * @var string
     */
    private $typTab;
    /**
     * @var string
     */
    private $datdeb;
    /**
     * @var string
     */
    private $datfin;
    /**
     * @var string
     */
    private $message;
    /**
     * @var string
     */
    private $codAct;
    /**
     * @var string
     */
    private $codFct;
    /**
     * @var string
     */
    private $gmrubr = StringConstants::GMRUBR_KEY;


    /**
     * @return mixed
     */
    public function getGmrubr()
    {
        return $this->gmrubr;
    }

    /**
     * @return mixed
     */
    public function getCodFct()
    {
        return $this->codFct;
    }

    /**
     * @param mixed $codFct
     *
     * @return \Gta\TracabiliteBundle\Entity\EgmhistLogObject
     */
    public function setCodFct($codFct)
    {
        $this->codFct = $codFct;

        return $this;
    }

    /**
     * @return string
     * @author Seif <ben.s@mipih.fr>
     */
    public function getCodhop()
    {
        return $this->codhop;
    }

    /**
     * @param $codhop
     *
     * @return $this
     * @author Seif <ben.s@mipih.fr>
     */
    public function setCodhop($codhop)
    {
        $this->codhop = $codhop;

        return $this;
    }

    /**
     * @return string
     * @author Seif <ben.s@mipih.fr>
     */
    public function getNomuti()
    {
        return $this->nomuti;
    }

    /**
     * @param $nomuti
     *
     * @return $this
     * @author Seif <ben.s@mipih.fr>
     */
    public function setNomuti($nomuti)
    {
        $this->nomuti = $nomuti;

        return $this;
    }


    /**
     * @return string
     * @author Seif <ben.s@mipih.fr>
     */
    public function getMatric()
    {
        return $this->matric;
    }

    /**
     * Description
     *
     * @param $matric
     *
     * @return $this
     * @author Seif <ben.s@mipih.fr>
     */
    public function setMatric($matric)
    {
        $this->matric = $matric;

        return $this;
    }

    /**
     *
     * Accéder à un élément ou tout le tableau
     *
     * @param string $key
     *
     * @return array|mixed|string
     * @throws \Gta\TracabiliteBundle\Exception\MissingTracabiliteParameterException
     * @author Seif <ben.s@mipih.fr>
     */
    public function getUcParams($key = '')
    {
        $key = trim($key);
        // retourner toute la liste
        if (strlen(trim($key)) == 0) {
            return $this->ucParams;
        }
        if (array_key_exists($key, $this->ucParams)) {
            return $this->ucParams[$key];
        }

        throw new MissingTracabiliteParameterException($key);
    }

    /**
     * @param $dbParams
     *
     * @return $this
     * @author Seif <ben.s@mipih.fr>
     */
    public function setUcParams($dbParams)
    {
        $this->ucParams = $dbParams;

        return $this;
    }

    /**
     * Récupérer un seul élément à la fois
     * avec la possibilité d'utiliser des indexes composés
     *
     * @param $key
     *
     * @return mixed
     * @author Seif <ben.s@mipih.fr>
     */
    public function getUcParam($key)
    {
        $accessorBuilder = PropertyAccess::createPropertyAccessorBuilder();
        $pa = $accessorBuilder
            ->enableExceptionOnInvalidIndex()
            ->getPropertyAccessor();

        return $pa->getValue($this->ucParams, $key);

    }

    /**
     * @param $key
     * @param $val
     *
     * @author Seif <ben.s@mipih.fr>
     */
    public function setUcParam($key, $val)
    {
        $this->ucParams[$key] = $val;
    }

    /**
     * @return mixed|string
     * @author Seif <ben.s@mipih.fr>
     */
    public function getServic()
    {
        if (!$this->hasServic()) {
            return ' ';
        }

        return $this->servic;
    }

    /**
     * @param $servic
     *
     * @return $this
     * @author Seif <ben.s@mipih.fr>
     */
    public function setServic($servic)
    {
        $this->servic = $servic;

        return $this;
    }

    /**
     * @return mixed|string
     * @author Seif <ben.s@mipih.fr>
     */
    public function getSertyp()
    {
        if (!$this->hasSertyp()) {
            return ' ';
        }

        return $this->sertyp;
    }

    /**
     * @param $sertyp
     *
     * @return $this
     * @author Seif <ben.s@mipih.fr>
     */
    public function setSertyp($sertyp)
    {
        $this->sertyp = $sertyp;

        return $this;
    }

    /**
     * @return string
     * @author Seif <ben.s@mipih.fr>
     */
    public function getTyptab()
    {
        return $this->typTab;
    }

    /**
     * @param $typtab
     *
     * @return $this
     * @author Seif <ben.s@mipih.fr>
     */
    public function setTyptab($typtab)
    {
        $this->typTab = $typtab;

        return $this;
    }

    /**
     * @return string
     * @author Seif <ben.s@mipih.fr>
     */
    public function getDatdeb()
    {
        return $this->datdeb;
    }

    /**
     * @param $datdeb
     *
     * @return $this
     * @author Seif <ben.s@mipih.fr>
     */
    public function setDatdeb($datdeb)
    {
        if (!is_string($datdeb)) {
            throw new \InvalidArgumentException('Only strings are allowed for that field');
        }
        $this->datdeb = $datdeb;

        return $this;
    }

    /**
     * @return string
     * @author Seif <ben.s@mipih.fr>
     */
    public function getDatfin()
    {
        return $this->datfin;
    }

    /**
     * @param $datfin
     *
     * @return $this
     * @author Seif <ben.s@mipih.fr>
     */
    public function setDatfin($datfin)
    {
        if (!is_string($datfin)) {
            throw new \InvalidArgumentException('Only strings are allowed for that field');
        }
        $this->datfin = $datfin;

        return $this;
    }

    /**
     * @return bool
     * @author Seif <ben.s@mipih.fr>
     */
    public function hasSertyp()
    {
        return strlen(trim($this->sertyp)) > 0;
    }

    /**
     * @return bool
     * @author Seif <ben.s@mipih.fr>
     */
    public function hasServic()
    {
        return strlen(trim($this->servic)) > 0;
    }

    /**
     * @return string
     * @author Seif <ben.s@mipih.fr>
     */
    public function getMessage()
    {
        return $this->message;
    }

    /**
     * @param $message
     *
     * @return \Gta\TracabiliteBundle\Entity\EgmhistLogObject
     * @author Seif <ben.s@mipih.fr>
     */
    public function setMessage($message)
    {
        if (!$message) {
            $this->message = '?';

            return $this;
        }
        $this->message = trim(ucfirst(substr($message, 0, 80)));

        return $this;
    }

    /**
     * @return string
     */
    public function getCodAct()
    {
        return $this->codAct;
    }

    /**
     * @param string $codAct
     *
     * @return \Gta\TracabiliteBundle\Entity\EgmhistLogObject
     */
    public function setCodAct($codAct)
    {
        $this->codAct = $codAct;

        return $this;
    }
}