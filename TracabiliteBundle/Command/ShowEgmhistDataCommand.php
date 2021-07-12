<?php

namespace Gta\TracabiliteBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Helper\TableCell;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class ShowEgmhistDataCommand
 *
 * @package Gta\TracabiliteBundle\Command
 * @author  Seif <ben.s@mipih.fr> (13/05/2019/ 14:11)
 * @version 19
 */
class ShowEgmhistDataCommand extends ContainerAwareCommand
{
    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this
            ->setName('trac:data')
            ->setDescription('Shows or deletes data from treacability database table')
            ->addOption(
                'mode',
                'm',
                InputOption::VALUE_OPTIONAL,
                '[1: Display data, 2: Truncate]',
                1
            )
            ->addOption(
                'limit',
                'l',
                InputOption::VALUE_OPTIONAL,
                'Limits displayed data',
                10
            );
    }

    /**
     * @param \Symfony\Component\Console\Input\InputInterface   $input
     * @param \Symfony\Component\Console\Output\OutputInterface $output
     *
     * @return int|null|void
     * @throws \Doctrine\DBAL\DBALException
     * @author Seif <ben.s@mipih.fr>
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        if (1 == $input->getOption('mode')) {
            $limit = $input->getOption('limit');
            $this->showAll($output, $limit);
        } elseif (2 == $input->getOption('mode')) {
            $this->truncate($output);
        }
    }

    /**
     * @param \Symfony\Component\Console\Output\OutputInterface $output
     *
     * @throws \Doctrine\DBAL\DBALException
     * @author Seif <ben.s@mipih.fr>
     */
    private function truncate(OutputInterface $output)
    {

        $output->writeln('<fg=red;bg=yellow>Start</>');
        $output->writeln('<fg=black;bg=white>Precessing...</>');
        /**
         * @var $connection \Gta\CoreBundle\DataBase\GtaConnection
         */
        $connection = $this->getContainer()->get('doctrine.dbal.default_connection');
        $stmt = $connection->prepare('DELETE FROM EGMHIST_TRACABILITE_TEST');
        $stmt->execute();
        $output->writeln($stmt->rowCount().' rows were deleted successfully');
        $output->writeln('<fg=yellow;bg=red>Done!</>');
    }

    /**
     * @param \Symfony\Component\Console\Output\OutputInterface $output
     *
     * @param null                                              $limit
     *
     * @throws \Doctrine\DBAL\DBALException
     * @author Seif <ben.s@mipih.fr>
     */
    private function showAll(OutputInterface $output, $limit = null)
    {
        /**
         * @var $connection \Gta\CoreBundle\DataBase\GtaConnection
         */
        $connection = $this->getContainer()->get('doctrine.dbal.default_connection');
        $cols = array(
            'DATEFF',
            'NOMUTI',
            'CODFCT',
            'CODACT',
            'MATRIC',
            'GMRUBR',
            'SERVIC',
            'SERTYP',
            'DATDEB',
            'DATFIN',
            'VALUE',
        );

        $sql = function () use ($cols, $limit) {
            $sql = 'SELECT '.implode(',', $cols).' FROM EGMHIST_TRACABILITE_TEST';

            return $sql.' ORDER BY DATEFF DESC';
        };
        $data = $connection
            ->query($sql())
            ->fetchAll();

        $queryCount = 'SELECT count(*) AS count FROM EGMHIST_TRACABILITE_TEST';
        $count = $connection->query($queryCount)->fetchColumn();
        $table = new Table($output);

        $table->setHeaders(
            array(
                array(
                    new TableCell('Count = '.$count, array('colspan' => count($cols) + 1)),
                ),
                array_merge(['#'], $cols),
            )
        );
        if (null !== $limit && 0 != $limit) {
            $applyLimit = true;
        }
        $i = 1;
        foreach ($data as $row) {
            array_unshift($row, $i);
            if (isset($applyLimit) && $i > $limit) {
                break;
            }
            $table->addRow($row);
            $i++;
        }
        $table->render();
    }
}
