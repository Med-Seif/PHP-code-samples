<?php
/**
 * User Seif <ben.s@mipih.fr>
 * Date time: 15/05/2019 12:13
 */

namespace Gta\TracabiliteBundle\Manager;

use Gta\TracabiliteBundle\Manager\TracabiliteConstants as Tc;

/**
 * Class TracabiliteConfig
 *
 * @package Gta\TracabiliteBundle\Manager
 * @author  Seif <ben.s@mipih.fr> (15/05/2019/ 15:08)
 * @version 19
 */
class TracabiliteConfig
{
    const TEMPLATE_KEY_INDEX = 0;

    private  static $multi_pose = [
        Tc::UC_MULTIPOSE            => [Tc::TEMPLATE_15]
    ];

    private  static $copier_coller = [
        Tc::UC_COPIERCOLLER           => [Tc::TEMPLATE_16]
    ];

    private static $deplacement = [
        Tc::UC_DEPLACEMENT_AJOUT     => [Tc::TEMPLATE_11],
        Tc::UC_DEPLACEMENT_MAJ       => [Tc::TEMPLATE_11],
        Tc::UC_DEPLACEMENT_SUP       => [Tc::TEMPLATE_11],
        Tc::UC_DEPLACEMENT_SUPP_AUTO => [Tc::TEMPLATE_1],
    ];

    private static $deplacement_ex = [
        Tc::UC_DEPLACEMENT_EX_AJOUT => [Tc::TEMPLATE_10],
        Tc::UC_DEPLACEMENT_EX_MAJ   => [Tc::TEMPLATE_10],
        Tc::UC_DEPLACEMENT_EX_SUP  => [Tc::TEMPLATE_14],
    ];

    private static $activite           = [
        Tc::UC_ACTIVITE_POSE_HORAIRE     => [Tc::TEMPLATE_3],
        Tc::UC_ACTIVITE_POSE_CONGE       => [Tc::TEMPLATE_12],
        Tc::UC_ACTIVITE_MAJ_HORAIRE      => [Tc::TEMPLATE_7],
        Tc::UC_ACTIVITE_REMUNERATION_MAJ => [Tc::TEMPLATE_4],
        Tc::UC_ACTIVITE_SUPPRESSION      => [Tc::TEMPLATE_6],
        Tc::UC_ACTIVITE_SUPP_CONGE       => [Tc::TEMPLATE_13],
        Tc::UC_ACTIVITE_UF_MAJ           => [Tc::TEMPLATE_5],
        Tc::UC_ACTIVITE_HDEB_MAJ         => [Tc::TEMPLATE_5],
        Tc::UC_ACTIVITE_HFIN_MAJ         => [Tc::TEMPLATE_5],
    ];
    private static $couverture         = [
        Tc::UC_COUVERTURE_COUVERT_AJOUT        => [Tc::TEMPLATE_2],
        Tc::UC_COUVERTURE_COUVERT_SUPP         => [Tc::TEMPLATE_2],
        Tc::UC_COUVERTURE_COUVRANT_SUPP        => [Tc::TEMPLATE_2],
        Tc::UC_COUVERTURE_COUVRANT_AFFECT_ADD  => [Tc::TEMPLATE_8],
        Tc::UC_COUVERTURE_COUVRANT_AFFECT_SUPP => [Tc::TEMPLATE_9],
    ];
    private static $affectation        = [
        Tc::UC_AFFECTATION_AJOUT => [],
        Tc::UC_AFFECTATION_MAJ   => [],
        Tc::UC_AFFECTATION_SUPP  => [],
    ];
    private static $contrat            = [
        Tc::UC_CONTRAT_AJOUT => [],
        Tc::UC_CONTRAT_MAJ   => [],
        Tc::UC_CONTRAT_SUPP  => [],
    ];
    private static $droit_conges       = [
        Tc::UC_DROIT_CONGE_AJOUT => [],
        Tc::UC_DROIT_CONGE_MAJ   => [],
        Tc::UC_DROIT_CONGE_SUPP  => [],
    ];
    private static $fiche              = [
        Tc::UC_FICHE_NOUV  => [],
        Tc::UC_FICHE_AJOUT => [],
        Tc::UC_FICHE_SUPP  => [],
        Tc::UC_FICHE_MAJ   => [],
    ];
    private static $validation_tableau = [
        Tc::UC_VALIDATION_TABLEAU => [],
    ];
    private static $valott             = [
        Tc::UC_VALO_TT_VALORISER       => [],
        Tc::UC_VALO_TT_GENERER_CP      => [],
        Tc::UC_VALO_TT_TRANSFERT_RH    => [],
        Tc::UC_VALO_TT_INIT_COMPTEUR   => [],
        Tc::UC_VALO_TT_MAJ_TTA         => [],
        Tc::UC_VALO_TT_REPARTITION_TTA => [],
    ];

    /**
     * @param $trigger
     *
     * @return int|null|string
     * @author Seif <ben.s@mipih.fr>
     */
    public static function getCodFct($trigger)
    {
        // récupérer la liste des codes activités
        $ymlConfig = self::getConfig();
        foreach ($ymlConfig as $codFct => $data) {
            if (isset($data[$trigger])) {
                return $codFct;
            }
        }

        return null;
    }

    /**
     * @param $trigger
     *
     * @return null
     * @author Seif <ben.s@mipih.fr>
     */
    public static function getMsgTemplate($trigger)
    {
        return self::getTriggerSubData($trigger, self::TEMPLATE_KEY_INDEX);
    }

    /**
     * @param $trigger
     *
     * @return bool
     * @author Seif <ben.s@mipih.fr>
     */
    public static function isConfiguredTrigger($trigger)
    {
        $ymlConfig = self::getConfig();
        foreach ($ymlConfig as $data) {
            if (array_key_exists($trigger, $data)) {
                return true;
            }
        }

        return false;
    }

    /**
     * @return mixed
     * @author Seif <ben.s@mipih.fr>
     */
    private static function getConfig()
    {
        return [
            Tc::CODE_FCT_DEPLAC   => self::$deplacement,
            Tc::CODE_FCT_DEPEXC   => self::$deplacement_ex,
            Tc::CODE_FCT_ACTIVITE => self::$activite,
            Tc::CODE_FCT_COUV     => self::$couverture,
            Tc::CODE_FCT_AFFECT   => self::$affectation,
            Tc::CODE_FCT_CONTRAT  => self::$contrat,
            Tc::CODE_FCT_DRTCONGE => self::$droit_conges,
            Tc::CODE_FCT_FICHE    => self::$fiche,
            Tc::CODE_FCT_VALIDTAB => self::$validation_tableau,
            Tc::CODE_FCT_VALOTT   => self::$valott,
            Tc::CODE_FCT_MULTI_POSE => self::$multi_pose,
            Tc::CODE_FCT_COPIER_COLLER => self::$copier_coller,
        ];
    }

    /**
     * @param $trigger
     * @param $index
     *
     * @return int|null|string
     * @author Seif <ben.s@mipih.fr>
     */
    private static function getTriggerSubData($trigger, $index)
    {
        $ymlConfig = self::getConfig();
        foreach ($ymlConfig as $data) {
            if (isset($data[$trigger][$index])) {
                return $data[$trigger][$index];
            }
        }

        return null;
    }

}