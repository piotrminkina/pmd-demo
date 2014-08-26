<?php

/*
 * This file is part of the PMDDemo package.
 *
 * (c) Piotr Minkina <projekty@piotrminkina.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace PMD\Bundle\FixturesBundle\Command;

use h4cc\AliceFixturesBundle\Fixtures\FixtureManagerInterface;
use h4cc\AliceFixturesBundle\ORM\SchemaToolInterface;
use PMD\Bundle\FixturesBundle\Iterator\FlipIterator;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Finder\Finder;
use Symfony\Component\HttpKernel\Bundle\BundleInterface;
use Symfony\Component\HttpKernel\KernelInterface;

/**
 * Class FilesLoadCommand
 * 
 * @author Piotr Minkina <projekty@piotrminkina.pl>
 * @package PMD\Bundle\FixturesBundle\Command
 */
class FilesLoadCommand extends ContainerAwareCommand
{
    /**
     * @inheritdoc
     */
    protected function configure()
    {
        $this
            ->setName('pmd:fixtures:files-load')
            ->setDescription('Load fixtures from files in Alice formats.')
            ->addOption(
                'manager',
                'm',
                InputOption::VALUE_REQUIRED,
                'Name of Alice manager to be used.',
                'default'
            )
            ->addOption(
                'bundle',
                'b',
                InputOption::VALUE_REQUIRED|InputOption::VALUE_IS_ARRAY,
                'Bundle as root directory where from load files. Type only one ALL to use all registered bundles.'
            )
            ->addOption(
                'root-dir',
                'r',
                InputOption::VALUE_REQUIRED|InputOption::VALUE_IS_ARRAY,
                'Root directory where from load files (a glob or a path).',
                array('app')
            )
            ->addOption(
                'dir',
                'd',
                InputOption::VALUE_REQUIRED|InputOption::VALUE_IS_ARRAY,
                'Directory in root directories where from load files (a glob or a path).',
                array('Resources/fixtures')
            )
            ->addOption(
                'filename',
                'f',
                InputOption::VALUE_REQUIRED,
                'A rule that file name must match (a regexp, a glob, or a simple name).',
                '*.alice.{yaml,php}'
            )
            ->addOption(
                'seed',
                null,
                InputOption::VALUE_REQUIRED,
                'Seed for random generator.',
                1
            )
            ->addOption(
                'locale',
                null,
                InputOption::VALUE_REQUIRED,
                'Locale for Faker provider.',
                'en_EN'
            )
            ->addOption(
                'drop',
                null,
                InputOption::VALUE_NONE,
                'Drop and create Schema before loading.'
            )
            ->addOption(
                'no-persist',
                null,
                InputOption::VALUE_NONE,
                'Do not persist loaded entities.'
            )
            ->addArgument(
                'file',
                InputArgument::OPTIONAL|InputArgument::IS_ARRAY,
                'Real path to file with fixtures'
            );
    }

    /**
     * @inheritdoc
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $manager = $this->getManager(
            $input->getOption('manager')
        );
        $dirsPaths = $this->prepareDirsPaths(
            $input->getOption('dir'),
            array_merge(
                $this->prepareBundlesPaths(
                    $input->getOption('bundle')
                ),
                $input->getOption('root-dir')
            )
        );
        $paths = $this->preparePaths(
            $dirsPaths,
            $input->getOption('filename'),
            $input->getArgument('file')
        );

        if ($input->getOption('drop')) {
            $schemaTool = $this->getSchemaTool(
                $input->getOption('manager')
            );
            $schemaTool->dropSchema();
            $schemaTool->createSchema();
        }

        $prototype = $manager->createFixtureSet();
        $prototype->setSeed($input->getOption('seed'));
        $prototype->setLocale($input->getOption('locale'));
        $prototype->setDoDrop(false);
        $prototype->setDoPersist(!$input->getOption('no-persist'));

        foreach ($paths as $path) {
            $set = clone $prototype;
            $set->addFile($path, pathinfo($path, PATHINFO_EXTENSION));

            $entities = $manager->load($set);

            $output->writeln("loaded " . count($entities) . " entities ... done.");
        }

    }

    /**
     * @param string $name
     * @return FixtureManagerInterface
     */
    protected function getManager($name)
    {
        $container = $this->getContainer();
        $serviceId = 'h4cc_alice_fixtures.manager';

        if ('default' !== $name) {
            $serviceId = sprintf('h4cc_alice_fixtures.%s_manager', $name);
        }

        return $container->get($serviceId);
    }

    /**
     * @param string $name
     * @return SchemaToolInterface
     */
    protected function getSchemaTool($name)
    {
        $container = $this->getContainer();
        $serviceId = 'h4cc_alice_fixtures.orm.schema_tool';

        if ('default' !== $name) {
            $serviceId = sprintf('h4cc_alice_fixtures.orm.%s_schema_tool', $name);
        }

        return $container->get($serviceId);
    }

    /**
     * @param string[] $bundlesNames
     * @return string[]
     */
    protected function prepareBundlesPaths(array $bundlesNames)
    {
        /** @var KernelInterface $kernel */
        $kernel = $this->getContainer()->get('kernel');
        $bundles = $this->getBundlesByNames($kernel, $bundlesNames);
        $bundlesPaths = $this->getBundlesPaths($bundles);

        return $bundlesPaths;
    }

    /**
     * @param string[] $dirs
     * @param string[] $rootsPaths
     * @return string[]
     */
    protected function prepareDirsPaths(array $dirs, array $rootsPaths)
    {
        $dirsPaths = array();

        foreach ($rootsPaths as $rootPath) {
            foreach ($dirs as $dir) {
                $dirsPaths[] = $rootPath . '/' . $dir;
            }
        }

        return $dirsPaths;
    }

    /**
     * @param string[] $dirsPaths
     * @param string $filename
     * @param string[] $paths
     * @return \Iterator
     */
    protected function preparePaths(array $dirsPaths, $filename, array $paths)
    {
        $finder = $this->createFinder()
            ->files()
            ->name($filename);

        while ($dirsPaths) {
            try {
                while ($dirsPaths) {
                    $dirPath = array_shift($dirsPaths);
                    $finder->in($dirPath);
                }
            } catch (\InvalidArgumentException $e) {
                continue;
            }
        }

        $finder->append(new FlipIterator(new \ArrayIterator($paths), true));

        return new FlipIterator($finder);
    }

    /**
     * @param KernelInterface $kernel
     * @param string[] $names
     * @return BundleInterface[]
     */
    protected function getBundlesByNames(KernelInterface $kernel, array $names)
    {
        if (1 == count($names) && 'ALL' == $names[0]) {
            $names = array_values($kernel->getBundles());
        } else {
            $names = array_map(array($kernel, 'getBundle'), $names);
        }

        return $names;
    }

    /**
     * @param BundleInterface[] $bundles
     * @return string[]
     */
    protected function getBundlesPaths(array $bundles)
    {
        return array_map(
            function (BundleInterface $bundle) {
                return $bundle->getPath();
            },
            $bundles
        );
    }

    /**
     * @return Finder
     */
    protected function createFinder()
    {
        return new Finder();
    }
}
