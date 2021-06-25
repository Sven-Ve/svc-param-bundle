<?php

namespace Svc\ParamBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class Configuration implements ConfigurationInterface
{
  public function getConfigTreeBuilder()
  {
    $treeBuilder = new TreeBuilder('svc_param');
    $rootNode = $treeBuilder->getRootNode();
 
    $rootNode
      ->children()
        ->booleanNode('debug')->defaultFalse()->info('Enable debug for parameter access?')->end()
      ->end();
    return $treeBuilder;

  }

}