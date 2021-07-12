<?php
/**
 * User Seif <ben.s@mipih.fr>
 * Date time: 26/06/2019 16:27
 */

namespace Gta\Domain\Exception;

use Throwable;

/**
 * Class MissingCouvertureException
 *
 * @package Gta\Domain\Exception
 * @author  Seif <ben.s@mipih.fr> (26/06/2019/ 16:28)
 * @version 19
 */
class MissingCouvertureException extends AbstractDomainException
{
    /**
     * MissingCouvertureException constructor.
     *
     * @param int             $code
     * @param \Throwable|null $previous
     */
    public function __construct($code = 0, \Throwable $previous = null)
    {
        parent::__construct('Couverture is missing', $code, $previous);
    }
}