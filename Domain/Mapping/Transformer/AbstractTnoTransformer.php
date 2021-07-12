<?php
/**
 * User Seif <ben.s@mipih.fr>
 * Date time: 11/04/2019 20:18
 */

namespace Gta\Domain\Mapping\Transformer;

use Gta\CoreBundle\Exception\Mapping\MissingRubriqueTNOException;
use Gta\CoreBundle\Repository\NomenclatureRepository;

/**
 * Class AbstractTnoTransformer
 *
 * @package Gta\Domain\Mapping\Transformer
 * @author  Seif <ben.s@mipih.fr> (11/04/2019/ 20:18)
 * @version 19
 */
abstract class AbstractTnoTransformer extends AbstractDataTransformer
{
    /**
     * @var array
     */
    protected $nomenclatureData;
    /**
     * @var \Gta\CoreBundle\Repository\NomenclatureRepository
     */
    protected $nomenclatureRepository;
    /**
     * @var string
     */
    protected $rubcod = '';

    /**
     * AbstractTnoTransformer constructor.
     *
     * @param \Gta\CoreBundle\Repository\NomenclatureRepository $nomenclatureRepository
     */
    public function __construct(NomenclatureRepository $nomenclatureRepository)
    {
        $this->nomenclatureRepository = $nomenclatureRepository;
    }

    /**
     * @return mixed
     */
    public function getNomenclatureData()
    {
        return $this->nomenclatureData;
    }

    /**
     * Sets data gathered from repositories (or wathever else) that will be used in look() method
     *
     * @return mixed
     * @throws \Gta\CoreBundle\Exception\Mapping\MissingRubriqueTNOException
     * @author Seif <ben.s@mipih.fr>
     */
    public function initData()
    {
        // récupérer les les services, faut passer null
        $rows = $this->nomenclatureRepository->findByRubcod($this->getParams(), $this->getRubcod(), false);
        $this->nomenclatureData = array_column($rows, null, 'tnoval');
    }

    /**
     * @return string
     * @author Seif <ben.s@mipih.fr>
     * @throws \Gta\CoreBundle\Exception\Mapping\MissingRubriqueTNOException
     */
    public function getRubcod()
    {
        if ('' === $this->rubcod) {
            throw new MissingRubriqueTNOException(
                'You should provide rubcod field (use AbstractTransformer::setRubcod method)'
            );
        }

        return $this->rubcod;
    }

    /**
     * @param $rubcod
     *
     * @author Seif <ben.s@mipih.fr>
     */
    public function setRubCod($rubcod)
    {
        $this->rubcod = trim(strtoupper($rubcod));
    }
}