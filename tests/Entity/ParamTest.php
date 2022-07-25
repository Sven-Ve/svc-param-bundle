<?php

declare(strict_types=1);

namespace Svc\ParamBundle\Tests\Entity;

use PHPUnit\Framework\TestCase;
use Svc\ParamBundle\Entity\Params;

class ParamTest extends TestCase
{

  public function testBoolean() {
    $param = new Params('test', null);
    $param->setParamType(Params::TYPE_BOOL);
    $this->assertNull($param->getValueBool());

    $param->setValueBool(true);
    $this->assertTrue($param->getValueBool());
    $this->assertEquals('true', $param->formatValue());
  }

  public function testString() {
    $param = new Params('test', null);
    $this->assertNull($param->getValueBool());

    $param->setValue("123");
    $this->assertEquals("123", $param->getValue());
  }
}
