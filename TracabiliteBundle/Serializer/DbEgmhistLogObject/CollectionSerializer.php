<?php
/**
 * User Seif <ben.s@mipih.fr>
 * Date time: 05/03/2019 15:54
 */

namespace Gta\TracabiliteBundle\Serializer\DbEgmhistLogObject;


use Gta\TracabiliteBundle\Entity\EgmhistLogObjectCollection;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

class CollectionSerializer implements NormalizerInterface
{
    /**
     * Sera utilisé pour normaliser chaque item de la collection (DbEgmhistLogObjectCollection)
     * puisqu'il est déjà fait pour le type d'objet DbEgmhistLogObject
     *
     * @var \Symfony\Component\Serializer\Normalizer\NormalizerInterface
     */
    private $itemSerializer;

    /**
     * CollectionSerializer constructor.
     *
     * @param \Symfony\Component\Serializer\Normalizer\NormalizerInterface $normalizer
     */
    public function __construct(NormalizerInterface $normalizer)
    {
        $this->itemSerializer = $normalizer;
    }

    /**
     * @param EgmhistLogObjectCollection $object
     * @param null                       $format
     * @param array                      $context
     *
     *
     * @return array|string|int|float|bool
     * @author Seif <ben.s@mipih.fr>
     */
    public function normalize($object, $format = null, array $context = array())
    {
        $ordre = 1;
        $normalizedCollection = array();
        foreach ($object as $row) {
            $normalizedCollection[] = $this->itemSerializer->normalize($row, null, $context, $ordre++);
        }

        return ['collection' => $normalizedCollection];
    }

    /**
     * Checks whether the given class is supported for normalization by this normalizer.
     *
     * @param mixed  $data   Data to normalize
     * @param string $format The format being (de-)serialized from or into
     *
     * @author Seif <ben.s@mipih.fr>
     * @return bool
     */
    public function supportsNormalization($data, $format = null)
    {
        return is_object($data) && $data instanceof EgmhistLogObjectCollection;
    }
}