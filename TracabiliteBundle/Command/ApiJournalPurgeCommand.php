<?php
/**
 * User Seif <ben.s@mipih.fr>
 * Date time: 10/01/2019 10:18
 */

namespace Gta\TracabiliteBundle\Command;


use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class ApiJournalPurgeCommand
 *
 * @package Gta\TracabiliteBundle\Command
 * @author  Seif <ben.s@mipih.fr>
 */
class ApiJournalPurgeCommand extends ContainerAwareCommand
{
    /**
     * @author Seif <ben.s@mipih.fr>
     */
    protected function configure()
    {
        $this
            ->setName('trac:api-truncate')
            ->setDescription('Purger la table des api journal');
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
        $output->writeln('<fg=red;bg=yellow>Start</>');
        $output->writeln('<fg=black;bg=white>Precessing...</>');
        /**
         * @var $connection \Gta\CoreBundle\DataBase\GtaConnection
         */
        $connection = $this->getContainer()->get('doctrine.dbal.default_connection');
        $stmt = $connection->prepare('DELETE FROM API_JOURNAL');
        $stmt->execute();
        $output->writeln($stmt->rowCount().' rows were deleted successfully');
        $output->writeln('<fg=yellow;bg=red>Done!</>');

    }
}