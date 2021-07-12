<?php
/**
 * User Seif <ben.s@mipih.fr>
 * Date time: 04/03/2019 20:29
 */

namespace Gta\TracabiliteBundle\Entity;

use Gta\CoreBundle\Contracts\Traits\ArrayAccessTrait;
use Gta\CoreBundle\Contracts\Traits\IteratorTrait;

/**
 * Class DbEgmhistLogObjectCollection
 *
 * @package Gta\TracabiliteBundle\Entity
 * @author  Seif <ben.s@mipih.fr> (05/03/2019/ 11:00)
 * @version 19
 */
class EgmhistLogObjectCollection implements \Iterator, \ArrayAccess, \Countable
{
    use ArrayAccessTrait {
        offsetSet as public traitOffsetSet;
    }
    use IteratorTrait;

    /**
     * Offset to set
     * @link    http://php.net/manual/en/arrayaccess.offsetset.php
     *
     * @param mixed $offset <p>
     *                      The offset to assign the value to.
     *                      </p>
     * @param mixed $value  <p>
     *                      The value to set.
     *                      </p>
     *
     * @return void
     * @since   5.0.0
     * @author  Seif <ben.s@mipih.fr>
     */
    public function offsetSet($offset, $value)
    {
        if (!is_int($offset) && null !== $offset) {
            throw new \InvalidArgumentException('Only integer (or null) values are allowed for keys');
        }
        if (!$value instanceof EgmhistLogObject) {
            throw new \InvalidArgumentException('Value must be of type '.EgmhistLogObject::class);
        }
        $this->traitOffsetSet($offset, $value);

    }

    /**
     * Count elements of an object
     * @link    http://php.net/manual/en/countable.count.php
     * @return int The custom count as an integer.
     * </p>
     * <p>
     * The return value is cast to an integer.
     * @since   5.1.0
     * @author  Seif <ben.s@mipih.fr>
     */
    public function count()
    {
        return count($this->store);
    }
}