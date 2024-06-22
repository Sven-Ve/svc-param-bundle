<?php

namespace Svc\ParamBundle\Tests\Controller;

use Exception;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class ParamControllerTest extends KernelTestCase
{

  public function testParamIndex(): void
  {
    $kernel = self::bootKernel();
    $client = new KernelBrowser($kernel);

    try {
      $client->request('GET', '/svc-param/en/');
    } catch (Exception $e) {
      dump($e);
    }
    $this->assertSame(200, $client->getResponse()->getStatusCode());
  }

}
