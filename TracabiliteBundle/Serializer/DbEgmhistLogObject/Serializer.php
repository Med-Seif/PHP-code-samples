<?php
/**
 * User Seif <ben.s@mipih.fr>
 * Date time: 08/01/2019 20:44
 */

namespace Gta\TracabiliteBundle\Serializer\DbEgmhistLogObject;

use Gta\TracabiliteBundle\Entity\EgmhistLogObject;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

/**
 * Class DbEgmhistLogObjectSerializer
 *
 * @package Gta\TracabiliteBundle\Serializer\Normalizer
 * @author  Seif <ben.s@mipih.fr>
 */
class Serializer implements DenormalizerInterface, NormalizerInterface
{
    use EgmhistLogObjectDenormalizerTrait,
        DbParamsNormalizerTrait;

    const  DATE_DEB_KEYS = ['datdeb', 'dr1deb', 'sedper', 'mf_datdeb', 'datcou',
        'dateff' // pour les déplacements exceptionnels
    ];
    // pour datcou qui est présente dans les deux tableaux, voir DE13123 le point 5.
    const  DATE_FIN_KEYS    = ['datfin', 'dr1fin', 'sefper', 'mf_datfin', 'datcou',
        'dateff' // pour les déplacements exceptionnels
    ];
    // NOTE IMPORTANTE : La relation [clef - donnée] est gérée à présent selon l'order de définition dans le tableau
    // des constantes, une fois la logique devient plus compliquée prévoir des tests en fonction des cofct ou/et codact dans les fonctions
    // extractXXX()...je sais bien que vous n'avez rien compris ... moi aussi
    const  DATE_TYPTAB_KEYS = ['typtab', 'typtabTTA'];

    /**
     * @param $instance
     *
     * @return bool
     * @author Seif <ben.s@mipih.fr>
     */
    protected function supportsAll($instance)
    {
        if (true === is_string($instance)) {
            return (class_exists($instance) && EgmhistLogObject::class === $instance);
        }

        return (true === is_object($instance)) && (EgmhistLogObject::class === get_class($instance));
    }
}