<?php

use PMD\Component\HttpKernel\Kernel;
use Symfony\Component\Config\Loader\LoaderInterface;

/**
 * Class AppKernel
 */
class AppKernel extends Kernel
{
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

            new PMD\Bundle\Demo\InvoiceBundle\PMDDemoInvoiceBundle(),
        );

        if (in_array($this->getEnvironment(), array('dev', 'test'))) {
            $bundles[] = new Symfony\Bundle\WebProfilerBundle\WebProfilerBundle();
            $bundles[] = new Sensio\Bundle\DistributionBundle\SensioDistributionBundle();
            $bundles[] = new Sensio\Bundle\GeneratorBundle\SensioGeneratorBundle();

            $bundles[] = new h4cc\AliceFixturesBundle\h4ccAliceFixturesBundle();

            $bundles[] = new PMD\Bundle\FixturesBundle\PMDFixturesBundle();
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
}
