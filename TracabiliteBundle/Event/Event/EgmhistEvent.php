<?php
/**
 * User Seif <ben.s@mipih.fr>
 * Date time: 05/10/2018 10:22
 */

namespace Gta\TracabiliteBundle\Event\Event;


use Symfony\Component\EventDispatcher\Event;

/**
 * Class DbCrudEvent
 *
 * @package Gta\TracabiliteBundle\Event\Event
 * @author  Seif <ben.s@mipih.fr>
 */
class EgmhistEvent extends Event
{
    const NAME = "db.crud"; // nom de l'evénement

    /**
     * @var string Nom de la table dans la DB
     */
    private $trigger;

    /**
     * @var array Paramètres de la requête
     */
    private $params;

    /**
     * @var string
     */
    private $codAct;

    private $codFct;

    /**
     * DbCrudEvent constructor.
     *
     * @param string     $trigger              Nom de l'objet surveillé
     * @param string     $codAct
     * @param            $codFct
     * @param array      $params               paramètres de la dernière requête base de données (ou autre) exécutée
     *
     * @param null|array $extraFormatterParams used to add extra data to be used in formatters (or elsewhere),
     *                                         notice that this has no relaion with extra data added by the logger
     *                                         itself and wich contains processors data (see monolog on symfony doc)
     */
    public function __construct(
        $trigger,
        $codAct,
        $codFct,
        $params,
        $extraFormatterParams = null
    ) {
        // injecter les paramètres extra dans les paramètres de formatage (la liste des champs à insérer dans la table detraçabilité)
        if (null !== $extraFormatterParams) {
            $params['extra'] = $extraFormatterParams;
        }
        $this->trigger = $trigger;
        $this->codAct = $codAct;
        $this->codFct = $codFct;
        $this->params = $params;
    }

    /**
     * Retourne l'objet courant sous forme d'un tableau
     *
     * @return array
     * @author Seif <ben.s@mipih.fr>
     */
    public function toArray()
    {
        return get_object_vars($this);
    }

}