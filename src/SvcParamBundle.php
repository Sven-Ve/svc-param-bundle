<?php

namespace Svc\ParamBundle;

use Symfony\Component\Config\Definition\Configurator\DefinitionConfigurator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use Symfony\Component\HttpKernel\Bundle\AbstractBundle;

class SvcParamBundle extends AbstractBundle
{
  public function getPath(): string
  {
    return \dirname(__DIR__);
  }

  public function configure(DefinitionConfigurator $definition): void
  {
    $definition->rootNode()
      ->children()
      ->booleanNode('enableDeleteParam')->defaultFalse()->info('Should the deletion of parameters be allowed in the user interface')->end()
      ->end();
  }

  /**
   * @param array<mixed> $config
   */
  public function loadExtension(array $config, ContainerConfigurator $container, ContainerBuilder $builder): void
  {
    $container->import('../config/services.yaml');

    $container->services()
      ->get('Svc\ParamBundle\Controller\EaParamsCrudController')
      ->arg(0, (bool) $config['enableDeleteParam']);

    $container->services()
      ->get('Svc\ParamBundle\Controller\ParamsController')
      ->arg(0, (bool) $config['enableDeleteParam']);
  }
}
