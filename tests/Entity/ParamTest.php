<?php

declare(strict_types=1);

namespace Svc\ParamBundle\Tests\Entity;

use PHPUnit\Framework\TestCase;
use Svc\ParamBundle\Entity\Params;
use Svc\ParamBundle\Enum\ParamType;

class ParamTest extends TestCase
{

  public function testBoolean(): void {
    $param = new Params('test', null);
    $param->setParamType(ParamType::BOOL);
    $this->assertNull($param->getValueBool());

    $param->setValueBool(true);
    $this->assertTrue($param->getValueBool());
    $this->assertEquals('true', $param->formatValue());
  }

  public function testString(): void {
    $param = new Params('test', null);
    $this->assertNull($param->getValueBool());

    $param->setValue("123");
    $this->assertEquals("123", $param->getValue());
  }

  public function testReadonly(): void {
    $param = new Params('test', null);
    $param->setReadonly(true);
    $this->assertTrue($param->isReadonly());
  }

  public function testComment(): void {
    $param = new Params('test', null);
    $param->setComment("123");

    $this->assertEquals("123", $param->getComment());
  }

}
