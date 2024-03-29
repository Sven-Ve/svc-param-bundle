<?php

namespace Svc\ParamBundle\Tests;

require_once(__dir__ . "/Dummy/AppKernelDummy.php");

use App\Kernel as AppKernel;
use Doctrine\Bundle\DoctrineBundle\DoctrineBundle;
use Svc\ParamBundle\Repository\ParamsRepository;
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

  private $builder;
  private $routes;
  private $extraBundles;

  /**
   * @param array             $routes  Routes to be added to the container e.g. ['name' => 'path']
   * @param BundleInterface[] $bundles Additional bundles to be registered e.g. [new Bundle()]
   */
  public function __construct(ContainerBuilder $builder = null, array $routes = [], array $bundles = [])
  {
    $this->builder = $builder;
    $this->routes = $routes;
    $this->extraBundles = $bundles;

    parent::__construct('test', true);
  }

  public function registerBundles(): iterable
  {
    return [
      new SvcParamBundle(),
      new FrameworkBundle(),
      new DoctrineBundle(),
      new TwigBundle(),
    ];
  }

  public function registerContainerConfiguration(LoaderInterface $loader): void
  {
    if (null === $this->builder) {
      $this->builder = new ContainerBuilder();
    }

    $builder = $this->builder;

    $loader->load(function (ContainerBuilder $container) use ($builder) {
      $container->merge($builder);

      $container->loadFromExtension(
        'framework',
        [
          'secret' => 'foo',
          'http_method_override' => false,
          'router' => [
            'resource' => 'kernel::loadRoutes',
            'type' => 'service',
            'utf8' => true,
          ],
        ]
      );

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

      $container->register(ParamsRepository::class)
      ->setAutoconfigured(true)
      ->setAutowired(true);

      $container->register(AppKernel::class)
      ->setAutoconfigured(true)
      ->setAutowired(true);

      $container->register('kernel', static::class)->setPublic(true);

      $kernelDefinition = $container->getDefinition('kernel');
      $kernelDefinition->addTag('routing.route_loader');
    });
  }

  /**
   * load bundle routes
   *
   * @param RoutingConfigurator $routes
   * @return void
   */
  protected function configureRoutes(RoutingConfigurator $routes)
  {
    $routes->import(__DIR__ . '/../config/routes.yaml')->prefix('/svc-param/{_locale}');
  }

  protected function configureContainer(ContainerBuilder $c, LoaderInterface $loader)
  {
  }
}
