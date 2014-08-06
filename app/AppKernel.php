<?php

use Symfony\Component\HttpKernel\Kernel;
use Symfony\Component\Config\Loader\LoaderInterface;

/**
 * Class AppKernel
 */
class AppKernel extends Kernel
{
    /**
     * @var string|null
     */
    protected $workDir;

    /**
     * @inheritdoc
     */
    public function __construct($environment, $debug)
    {
        parent::__construct(
            $environment,
            $debug
        );
        $this->workDir = $this->getWorkDir();
    }

    /**
     * @inheritdoc
     */
    public function registerBundles()
    {
        $bundles = array(
            new Symfony\Bundle\FrameworkBundle\FrameworkBundle(),
            new Symfony\Bundle\SecurityBundle\SecurityBundle(),
            new Symfony\Bundle\TwigBundle\TwigBundle(),
            new Symfony\Bundle\MonologBundle\MonologBundle(),
            new Symfony\Bundle\SwiftmailerBundle\SwiftmailerBundle(),
            new Symfony\Bundle\AsseticBundle\AsseticBundle(),
            new Doctrine\Bundle\DoctrineBundle\DoctrineBundle(),
            new Sensio\Bundle\FrameworkExtraBundle\SensioFrameworkExtraBundle(),

            new Egulias\ListenersDebugCommandBundle\EguliasListenersDebugCommandBundle(),
            new JMS\SerializerBundle\JMSSerializerBundle(),
            new FOS\RestBundle\FOSRestBundle(),

            new PMD\Bundle\DoctrineBundle\PMDDoctrineBundle(),
            new PMD\Bundle\RoutingBundle\PMDRoutingBundle(),
            new PMD\Bundle\Resource\ResolverBundle\PMDResourceResolverBundle(),
            new PMD\Bundle\WorkflowBundle\PMDWorkflowBundle(),
            new PMD\Bundle\StateMachineBundle\PMDStateMachineBundle(),
            new PMD\Bundle\FrontendBundle\PMDFrontendBundle(),

            new PMD\WorkflowDemoBundle\PMDWorkflowDemoBundle(),
        );

        if (in_array($this->getEnvironment(), array('dev', 'test'))) {
            $bundles[] = new Symfony\Bundle\WebProfilerBundle\WebProfilerBundle();
            $bundles[] = new Sensio\Bundle\DistributionBundle\SensioDistributionBundle();
            $bundles[] = new Sensio\Bundle\GeneratorBundle\SensioGeneratorBundle();
        }

        return $bundles;
    }

    /**
     * @inheritdoc
     */
    public function registerContainerConfiguration(LoaderInterface $loader)
    {
        $loader->load(__DIR__.'/config/config_'.$this->getEnvironment().'.yml');
    }

    /**
     * Gets the application work dir.
     *
     * @return string The application work dir
     */
    public function getWorkDir()
    {
        if (null === $this->workDir) {
            $this->workDir = dirname($this->getRootDir()).'/var';
        }

        return $this->workDir;
    }

    /**
     * @inheritdoc
     */
    public function getCacheDir()
    {
        return $this->workDir.'/cache/'.$this->environment;
    }

    /**
     * @inheritdoc
     */
    public function getLogDir()
    {
        return $this->workDir.'/logs';
    }
}
