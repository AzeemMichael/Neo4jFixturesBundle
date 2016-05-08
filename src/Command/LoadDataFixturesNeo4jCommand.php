<?php

namespace PandawanTechnology\Neo4jFixturesBundle\Command;

use PandawanTechnology\Neo4jBundle\Command\AbstractNeo4jCommand;
use PandawanTechnology\Neo4jFixturesBundle\DataFixtures\DataFixturesLoader;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class LoadDataFixturesNeo4jCommand extends AbstractNeo4jCommand
{
    /**
     * @inheritDoc
     */
    protected function configure()
    {
        $this->setName('neo4j:fixtures:load')
            ->setDescription('Load Neo4j data fixtures to your database.')
            ->addOption('fixtures', null, InputOption::VALUE_OPTIONAL | InputOption::VALUE_IS_ARRAY, 'The directory to load data fixtures from.')
            ->addOption('conn', null, InputOption::VALUE_OPTIONAL, 'The connection alias to use for this command.')
            ;
    }

    /**
     * @inheritDoc
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        if (!$input->getOption('conn')) {
            if (!$conn = $this->getNeo4jMasterConnection()) {
                $output->writeln('<error>No master connection has been defined.</error>');

                return 1;
            }
        } else {
            $conn = $this->getNeo4jConnection($input->getOption('conn'));
        }

        if ($dirOrFile = $input->getOption('fixtures')) {
            $paths = is_array($dirOrFile) ? $dirOrFile : [$dirOrFile];
        } else {
            $paths = [];

            foreach ($this->getApplication()->getKernel()->getBundles() as $bundle) {
                $paths[] = $bundle->getPath().'/DataFixtures/Neo4j';
            }
        }

        $loader = new DataFixturesLoader($this->getContainer());

        if (!$fixtures = $loader->getFixtures()) {
            throw new \InvalidArgumentException(sprintf('Could not find any fixtures to load in: %s', "\n\n- ".implode("\n- ", $paths)));
        }

        $executor = new Executor($conn);
        $executor->execute($fixtures);
    }
}