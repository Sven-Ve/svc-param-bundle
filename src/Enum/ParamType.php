<?php

namespace Svc\ParamBundle\Enum;

enum ParamType: int
{
  case STRING = 1;
  case BOOL = 2;
  case DATETIME = 3;
  case DATE = 4;
  case INTEGER = 5;

  public function label(): string
  {
    return match ($this) {
      ParamType::STRING => 'string',
      ParamType::BOOL => 'boolean',
      ParamType::DATETIME => 'datetime',
      ParamType::DATE => 'date',
      ParamType::INTEGER => 'integer',
    };
  }
}
