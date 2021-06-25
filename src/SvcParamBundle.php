<?php

namespace Svc\ParamBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;

class SvcParamBundle extends Bundle {

  public function getPath(): string
  {
      return \dirname(__DIR__);
  }
}