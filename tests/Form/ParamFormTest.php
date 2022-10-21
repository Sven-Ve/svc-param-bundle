<?php

namespace Svc\ParamBundle\Tests\Form;

use Svc\ParamBundle\Entity\Params;
use Svc\ParamBundle\Enum\ParamType;
use Svc\ParamBundle\Form\ParamsType;
use Symfony\Component\Form\Test\TypeTestCase;

class ParamFormTest extends TypeTestCase
{
  /**
   * @test
   */
  public function testFormIsSubmittedSuccessfully()
  {
    $this->assertTrue(true);
  //  return;

    $model = new Params('testBool','Input');
    $model->setParamType(ParamType::STRING);

    $form = $this->factory->create(ParamsType::class, $model);

    $form->submit(['Value' => 'Output']);

    $this->assertTrue($form->isSynchronized());
    $this->assertSame('Output', $form->getData()->getValue());
  }
}
