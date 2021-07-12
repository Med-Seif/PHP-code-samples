<?php
/**
 * User Seif <ben.s@mipih.fr>
 * Date time: 24/04/2019 20:23
 */

namespace Gta\Domain\Mapping\Mapper;

/**
 * Class SearchIntervenantMapper
 *
 * @package Gta\MedicalBundle\Mapper
 * @author  Seif <ben.s@mipih.fr> (25/04/2019/ 10:07)
 * @version 19
 */
class SearchIntervenantMapper extends AbstractMapper
{

    /**
     * @param array $data
     * @param array $extra
     *
     * @return array
     * @author Seif <ben.s@mipih.fr>
     */
    public function mapData(array $data, array $extra = [])
    {
        $mapped = [];
        foreach ($data as $row) {
            $row['service'] = [
                'typtab' => $row['typtab'],
                'servic' => $row['servic'],
                'sertyp' => $row['sertyp'],
                'sercon' => $row['sercon'],
                'serlib' => $row['serlib'],
            ];
            $row['esmod'] = $this->transformEntreeSortie($row['entree'], $row['sortie']);
            $row = $this->removeKeys($row, ['servic', 'sertyp', 'typtab', 'serlib', 'sercon']);
            $mapped[] = $row;
        }

        return $mapped;
    }

    /**
     * @param $entree
     * @param $sortie
     *
     * @return null|string
     * @author Seif <ben.s@mipih.fr>
     */
    private function transformEntreeSortie($entree, $sortie)
    {
        if (null !== $entree && null !== $sortie && $sortie !== '31/12/3000') {
            return 'E/S';
        } elseif (null !== $entree && (null === $sortie || $sortie === '31/12/3000')) {
            return 'E';
        } elseif (null === $entree && null !== $sortie) {
            return 'S';
        }

        return null;
    }


}