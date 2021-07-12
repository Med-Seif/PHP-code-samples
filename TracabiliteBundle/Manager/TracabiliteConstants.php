<?php
/**
 * User Seif <ben.s@mipih.fr>
 * Date time: 27/02/2019 11:01
 */

namespace Gta\TracabiliteBundle\Manager;

/**
 * Class TracabilteConstants
 *
 * @package Gta\TracabiliteBundle\Log
 * @author  Seif <ben.s@mipih.fr> (27/02/2019/ 11:09)
 * @version 19
 */
class TracabiliteConstants
{

    //Multi-pose
    const UC_MULTIPOSE             = 'MULTPOS';

    //Copier/coller
    const UC_COPIERCOLLER          = 'COPCOLL';

    // Déplacement
    const UC_DEPLACEMENT_AJOUT     = 'AJTDEP';
    const UC_DEPLACEMENT_MAJ       = 'MAJDEP';
    const UC_DEPLACEMENT_SUP       = 'SUPDEP';
    const UC_DEPLACEMENT_SUPP_AUTO = 'SUPDEPA';

    // Déplacement exceptionnel
    const UC_DEPLACEMENT_EX_AJOUT = 'AJTDEX';
    const UC_DEPLACEMENT_EX_MAJ   = 'MAJDEX';
    const UC_DEPLACEMENT_EX_SUP   = 'SUPDEX';

    // Mise à jour activité TS col
    const UC_ACTIVITE_POSE_HORAIRE     = 'POSHOR';
    const UC_ACTIVITE_POSE_CONGE       = 'POSHCG';
    const UC_ACTIVITE_MAJ_HORAIRE      = 'MAJHOR';
    const UC_ACTIVITE_REMUNERATION_MAJ = 'MAJREM';
    const UC_ACTIVITE_SUPPRESSION      = 'DELHOR';
    const UC_ACTIVITE_SUPP_CONGE       = 'DELHCG';
    const UC_ACTIVITE_UF_MAJ           = 'MAJUF';
    const UC_ACTIVITE_HDEB_MAJ         = 'MAJHDEB';
    const UC_ACTIVITE_HFIN_MAJ         = 'MAJHFIN';

    // Couverture
    const UC_COUVERTURE_COUVERT_AJOUT        = 'AJCVERT';
    const UC_COUVERTURE_COUVERT_SUPP         = 'DCVERT'; // suppression d'une ligne de gmcouv
    const UC_COUVERTURE_COUVRANT_SUPP        = 'DCVRANT'; // mettre à blanc les champs de droite de gmcouv à partir du TSCOLL
    const UC_COUVERTURE_COUVRANT_AFFECT_SUPP = 'SUPCVRN'; // mettre à blanc en changeant l'affectation à un autre
    const UC_COUVERTURE_COUVRANT_AFFECT_ADD  = 'AJCVRNT'; // renseigner les champs XXXSEN de la table gmcouv, affecter un couvrat à une couverture existente

    // Affectation
    const UC_AFFECTATION_AJOUT = 'AJTAFF';
    const UC_AFFECTATION_MAJ   = 'MAJAFF';
    const UC_AFFECTATION_SUPP  = 'SUPAFF';

    // Validation tableau
    const UC_VALIDATION_TABLEAU = 'VALTAB';

    // Valo tt
    const UC_VALO_TT_MAJ_TTA         = 'MAJTTA';
    const UC_VALO_TT_INIT_COMPTEUR   = 'INICOMP';
    const UC_VALO_TT_VALORISER       = 'VALVTT';
    const UC_VALO_TT_GENERER_CP      = 'GCPVTT';
    const UC_VALO_TT_TRANSFERT_RH    = 'TRHVTT';
    const UC_VALO_TT_REPARTITION_TTA = 'REPATTA';

    // Contrat
    const UC_CONTRAT_AJOUT = 'AJTCTT';
    const UC_CONTRAT_MAJ   = 'MAJCTT';
    const UC_CONTRAT_SUPP  = 'SUPCTT';

    // Droit de congés
    const UC_DROIT_CONGE_AJOUT = 'AJTDTCG';
    const UC_DROIT_CONGE_MAJ   = 'MAJDTCG';
    const UC_DROIT_CONGE_SUPP  = 'SUPDTCG';

    // Fiche
    const UC_FICHE_NOUV  = 'NEWFCHG';
    const UC_FICHE_AJOUT = 'AJTFCHG';
    const UC_FICHE_MAJ   = 'SUPFCHG';
    const UC_FICHE_SUPP  = 'MAJFCHG';

    // Codes fonctionnalités
    const CODE_FCT_AFFECT   = 'affect';
    const CODE_FCT_CONTRAT  = 'contrat';
    const CODE_FCT_COUV     = 'couv';
    const CODE_FCT_DEPLAC   = 'deplac';
    const CODE_FCT_DRTCONGE = 'drtconge';
    const CODE_FCT_FICHE    = 'fiche';
    const CODE_FCT_ACTIVITE = 'activite';
    const CODE_FCT_DEPEXC   = 'depexc';
    const CODE_FCT_VALOTT   = 'valott';
    const CODE_FCT_VALIDTAB = 'validtab';
    const CODE_FCT_MULTI_POSE = 'multpose';
    const CODE_FCT_COPIER_COLLER = 'copiecol';

    // Templates de messages
    const TEMPLATE_1 = "dephor ~ ', Activité: ' ~ gatActDep(existing_activity, existing_activity_next, dephor) ~ ',' ~ getHeure(dephdb) ~ '-' ~ getHeure(dephfn) ~ ',' ~ depcom";
    const TEMPLATE_2 = "'Ts Col' ~ ':' ~ ts_plsdat ~ ',' ~ ts_typhor ~ ',' ~ 'Act:' ~ ts_codact";
    const TEMPLATE_3 = " ts_typhor ~' '~ ts_typhor_next ~ ', Act:' ~ ts_codact ~ ',' ~ getRemuneration(ts_plscmp,ts_plsadd) ";
    const TEMPLATE_4 = "plsdate ~ ',' ~ getTyph(plshor, secondRow) ~ ',' ~ 'Act:' ~ plsact ~ ',' ~ getRemuneration(plscmp,plsadd) ~ '(' ~ getRemuneration(data[\"plscmp\"],data[\"plsadd\"]) ~ '), UF : ' ~ data[\"plsuf\"]";
    const TEMPLATE_5 = "plsdate ~ ',' ~ getTyph(plshor, secondRow) ~ ',' ~ 'Act:' ~ plsact ~ getModifHeure(plshfi, data[\"plshfi\"]) ~', ' ~ getModif(getRemuneration(plscmp,plsadd), getRemuneration(data[\"plscmp\"],data[\"plsadd\"])) ~ ', UF : ' ~ getModif(plsuf, data[\"plsuf\"])";
    const TEMPLATE_6 = "ts_typhor ~' '~ ts_typhor_next ~ ', Act:' ~ existing_activity[\"plsact\"] ~ ',' ~ getRemuneration(existing_activity[\"plscmp\"],existing_activity[\"plsadd\"])";
    const TEMPLATE_7 = "ts_typhor ~' '~ ts_typhor_next ~ ', Act:' ~ ts_codact ~ '(' ~ getActModif(existing_activity, existing_activity_next) ~ ')' ~ ',' ~ getModif(getRemuneration(ts_plscmp,ts_plsadd), getExistingRemu(existing_activity,existing_activity_next)) ";
    // j'ai laissé 8 et 9 identiques, à ne pas toucher
    const TEMPLATE_8 = "dateFormat(datcou) ~ ', ' ~ horcou ~ ', ' ~ 'Act: ' ~ actcou ~ ', ' ~ 'Couvert par ' ~ couvrant_name ~ ' (' ~ matsen ~ ')'";
    const TEMPLATE_9 = "dateFormat(datcou) ~ ', ' ~ horcou ~ ', ' ~ 'Act: ' ~ actcou ~ ', ' ~ 'Couvert par ' ~ couvrant_name ~ ' (' ~ matsen ~ ')'";
    const TEMPLATE_10 =  "dateff ~ ',' ~ typhor ~ ',' ~ heured ~ '-' ~ heuref ~ ',' ~ commentaire";
    const TEMPLATE_11 = "typhor ~ ', Activité: ' ~ actlib ~ ',' ~ heured ~ '-' ~ heuref ~ ',' ~ commentaire";
    const TEMPLATE_12 = " ts_typhor ~' '~ ts_typhor_next ~ ', Act:' ~ ts_codact ~ '(' ~ cg_class ~ ')' ";
    const TEMPLATE_13 = "ts_typhor ~' '~ ts_typhor_next ~ ', Act:' ~ existing_activity[\"plsact\"]~ '(' ~ existing_activity[\"codpos\"] ~ ')'";
    const TEMPLATE_14 =  "dateff ~ ',' ~ typhor ~ ',' ~ heured";
    const TEMPLATE_15 = "'Act:' ~ activite ~ ',' ~ getRemuneration(plscmp,plsadd)";
    const TEMPLATE_16 = "'Orig.: ' ~ intervenant[\"nom\"] ~ ' ' ~ substr(intervenant[\"prenom\"],0,1) ~ '.(' ~ matric_src ~ ') sur: ' ~ datdeb_src ~ '-' ~ datfin_src";
}