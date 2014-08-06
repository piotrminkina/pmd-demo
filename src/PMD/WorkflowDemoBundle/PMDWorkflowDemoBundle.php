<?php

/*
 * This file is part of the PMDDemo package.
 *
 * (c) Piotr Minkina <projekty@piotrminkina.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace PMD\WorkflowDemoBundle;

use Doctrine\Bundle\DoctrineBundle\DependencyInjection\Compiler\DoctrineOrmMappingsPass;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;

/**
 * Class PMDWorkflowDemoBundle
 * 
 * @author Piotr Minkina <projekty@piotrminkina.pl>
 * @package PMD\WorkflowDemoBundle
 */
class PMDWorkflowDemoBundle extends Bundle
{
    /**
     * @inheritdoc
     */
    public function build(ContainerBuilder $container)
    {
        parent::build($container);

        $this->registerMappingPasses($container);
    }

    /**
     * @param ContainerBuilder $container
     */
    protected function registerMappingPasses(ContainerBuilder $container)
    {
        $modelDir = $this->getPath() . '/Resources/config/doctrine/model';
        $mappings = array(
            $modelDir => 'PMD\WorkflowDemoBundle\Model',
        );

        $this->registerOrmMappingPass($container, $mappings);
    }

    /**
     * @param ContainerBuilder $container
     * @param array $mappings
     */
    protected function registerOrmMappingPass(
        ContainerBuilder $container,
        array $mappings
    ) {
        $ormCompilerClass = 'Doctrine\Bundle\DoctrineBundle\DependencyInjection\Compiler\DoctrineOrmMappingsPass';

        if (class_exists($ormCompilerClass)) {
            $container->addCompilerPass(
                DoctrineOrmMappingsPass::createXmlMappingDriver($mappings)
            );
        }
    }
}
