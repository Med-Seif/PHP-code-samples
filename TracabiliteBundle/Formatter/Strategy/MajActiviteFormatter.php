<?php
/**
 * Created by PhpStorm.
 * User: ditte.t
 * Date: 06/05/2019
 * Time: 10:30
 */

namespace Gta\TracabiliteBundle\Formatter\Strategy;


use Gta\MedicalBundle\Model\Remuneration;
use Gta\TracabiliteBundle\Formatter\AbstractTracabiliteFormatter;
use Gta\TracabiliteBundle\Manager\TracabiliteConstants as Tc;

/**
 * Class MajActiviteFormatter
 *
 * @package Gta\TracabiliteBundle\Formatter\Strategy
 * @author  Seif <ben.s@mipih.fr> (23/12/2019/ 17:38)
 * @version 19
 */
class MajActiviteFormatter extends AbstractTracabiliteFormatter
{
    /*
 * Modification UF :
    [loc] : [dat], [typh], Activité : [act], [rem], UF : [ufnew] ([ufold])

    Modification rémunération :
    [loc] : [dat], [typh], Activité : [act], [remnew] ([remold)], UF : [uf]
 */

    /**
     * Formater les données afin de les rendre prêtes pour une insertion dans la BD (EGMHIST)
     *
     * @return \Gta\TracabiliteBundle\Entity\EgmhistLogObject
     * @author Seif <ben.s@mipih.fr>
     */
    public function format()
    {
        return $this->getDbEgmhistLogObject();
    }

    /**
     * Génération message de traçabilité à insérer dans la colonne value
     *
     * @return string
     * @throws \Gta\TracabiliteBundle\Exception\MessageDesciptionGenerationException
     * @throws \Gta\TracabiliteBundle\Exception\MissingTracabiliteParameterException
     * @throws \ReflectionException
     * @author Seif <ben.s@mipih.fr>
     * @author tditt
     */
    public function generateMessage()
    {
        return $this->generateLibAction();
    }

    /**
     * @param $trigger
     *
     * @return bool|mixed
     * @author Seif <ben.s@mipih.fr>
     */
    public function supports($trigger)
    {
        return in_array($trigger, [Tc::UC_ACTIVITE_REMUNERATION_MAJ, Tc::UC_ACTIVITE_UF_MAJ]);
//        return Tc::UC_ACTIVITE_REMUNERATION_MAJ === $trigger;
    }
}