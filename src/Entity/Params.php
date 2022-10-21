<?php

namespace Svc\ParamBundle\Entity;

use DateTime;
use DateTimeInterface;
use Doctrine\ORM\Mapping as ORM;
use Svc\ParamBundle\Enum\ParamType;
use Svc\ParamBundle\Repository\ParamsRepository;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: ParamsRepository::class)]
#[UniqueEntity(fields: ['name'], message: 'Parameter already exists.')]
class Params
{
  private const DATETIMEFORMAT = 'Y-m-d H:i:s';
  private const DATEFORMAT = 'Y-m-d';

  #[ORM\Id]
  #[ORM\GeneratedValue]
  #[ORM\Column()]
  private ?int $id = null;

  #[ORM\Column(length: 30, unique: true)]
  #[Assert\NotBlank]
  #[Assert\Length(min: 3, max: 30, minMessage: 'Your parameter name must be at least {{ limit }} characters long', maxMessage: 'Your parameter name cannot be longer than {{ limit }} characters')]
  private ?string $name = null;

  #[ORM\Column(nullable: true)]
  private ?string $value = null;

  #[ORM\Column(type: 'smallint', enumType: ParamType::class)]
  private ParamType $paramType = ParamType::STRING;

  #[ORM\Column(length: 80, nullable: true)]
  private ?string $comment = null;

  public function __construct($name = null, $val = null)
  {
    if ($name) {
      $this->setName($name);
      $this->setValue($val);
    }
  }

  public function getId(): ?int
  {
    return $this->id;
  }

  public function getName(): ?string
  {
    return $this->name;
  }

  public function setName(string $name): self
  {
    $this->name = $name;

    return $this;
  }

  public function getValue(): ?string
  {
    return $this->value;
  }

  public function setValue(?string $value): self
  {
    $this->value = $value;

    return $this;
  }

  public function getParamType(): ParamType
  {
    return $this->paramType;
  }

  public function setParamType(ParamType $paramType): self
  {
    $this->paramType = $paramType;

    return $this;
  }

  public function getValueBool(): ?bool
  {
    if ($this->value === null) {
      return null;
    }

    return $this->value == '1';
  }

  public function setValueBool(bool $val): self
  {
    $this->value = $val ? '1' : '0';

    return $this;
  }

  public function getValueDateTime(): ?DateTime
  {
    if (!$this->value) {
      return null;
    }

    return DateTime::createFromFormat(self::DATETIMEFORMAT, $this->value);
  }

  public function setValueDateTime(DateTime $val): void
  {
    $this->value = $val->format(self::DATETIMEFORMAT);
  }

  public function getValueDate(): ?DateTimeInterface
  {
    if (!$this->value) {
      return null;
    }

    return DateTime::createFromFormat(self::DATEFORMAT, $this->value);
  }

  public function setValueDate(DateTimeInterface $val): void
  {
    $this->value = $val->format(self::DATEFORMAT);
  }

  public function formatValue(): ?string
  {
    if ($this->value === null) {
      return null;
    }

    return match ($this->paramType) {
      ParamType::BOOL => $this->getValueBool() ? 'true' : 'false',
      default => $this->value,
    };
  }

  public function getTypeText(): string
  {
    return $this->paramType->label();
  }

  public function getComment(): ?string
  {
    return $this->comment;
  }

  public function setComment(?string $comment): self
  {
    $this->comment = $comment;

    return $this;
  }

  public static function getTypesForChoices(): array
  {
    $choices = [];
    foreach (ParamType::cases() as $paramType) {
      $choices[$paramType->label()] = $paramType->value;
    }

    return $choices;
  }
}
