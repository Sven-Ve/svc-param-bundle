<?php

namespace Svc\ParamBundle\Tests;

use Doctrine\Bundle\DoctrineBundle\DoctrineBundle;
use Symfony\Component\HttpKernel\Kernel;
use Svc\ParamBundle\SvcParamBundle;
use Symfony\Bundle\FrameworkBundle\FrameworkBundle;
use Symfony\Bundle\FrameworkBundle\Kernel\MicroKernelTrait;
use Symfony\Component\Config\Loader\LoaderInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Routing\Loader\Configurator\RoutingConfigurator;
use Symfony\Bundle\TwigBundle\TwigBundle;

/**
 * Test kernel
 */
class SvcParamTestingKernel extends Kernel
{
 
  use MicroKernelTrait;

  public function registerBundles(): iterable
  {
    yield new FrameworkBundle();
    yield new TwigBundle();
    yield new SvcParamBundle();
    yield new DoctrineBundle();
  }

  protected function configureContainer(ContainerBuilder $container, LoaderInterface $loader): void
  {
    $config = [
      'http_method_override' => false,
      'secret' => 'foo-secret',
      'test' => true,
    ];

    $container->loadFromExtension('framework', $config);

    $container->loadFromExtension('doctrine', [
      'dbal' => [
//          'override_url' => true,
        'driver' => 'pdo_sqlite',
        'url' => 'sqlite:///' . $this->getCacheDir() . '/app.db',
      ],
      'orm' => [
        'auto_generate_proxy_classes' => true,
        'auto_mapping' => true,
        'enable_lazy_ghost_objects' => true,
        'report_fields_where_declared' => true
      ],
    ]);
  }

  /**
   * load bundle routes.
   *
   * @return void
   */
  private function configureRoutes(RoutingConfigurator $routes)
  {
    $routes->import(__DIR__ . '/../config/routes.yaml')->prefix('/svc-param/{_locale}');
  }
}
