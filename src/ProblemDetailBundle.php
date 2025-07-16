<?php

namespace Rami\ProblemDetailBundle;

use Symfony\Component\Config\Definition\Configurator\DefinitionConfigurator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use Symfony\Component\HttpKernel\Bundle\AbstractBundle;

class ProblemDetailBundle extends AbstractBundle
{
    public function getPath(): string
    {
        $reflected = new \ReflectionObject($this);

        return \dirname($reflected->getFileName(), 2);
    }

    public function configure(DefinitionConfigurator $definition): void
    {
        //$definition->import('../config/definition.php');
    }

    public function loadExtension(array $config, ContainerConfigurator $container, ContainerBuilder $builder): void
    {
        $container->import('../config/services.php');
    }
}