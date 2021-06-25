<?php

namespace Svc\ParamBundle\Entity;

use Svc\ParamBundle\Repository\ParamsRepository;
use DateTime;
use DateTimeInterface;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @ORM\Entity(repositoryClass=ParamsRepository::class)
 * @UniqueEntity(fields={"name"}, message="Parameter already exists.")
 */
class Params
{

  private const DATETIMEFORMAT = "Y-m-d H:i:s";
  private const DATEFORMAT = "Y-m-d";

  const TYPE_STRING = 1;
  const TYPE_BOOL = 2;
  const TYPE_DATETIME = 3;
  const TYPE_DATE = 4;

  const TYPE_LIST = [
    self::TYPE_STRING   => 'string',
    self::TYPE_BOOL     => 'boolean',
    self::TYPE_DATETIME => 'datetime',
    self::TYPE_DATE     => 'date',
  ];

  /**
   * @ORM\Id
   * @ORM\GeneratedValue
   * @ORM\Column(type="integer")
   */
  private $id;

  /**
   * @ORM\Column(type="string", length=30, unique=true)
   * @Assert\NotBlank
   * @Assert\Length(
   *      min = 3,
   *      max = 30,
   *      minMessage = "Your parameter name must be at least {{ limit }} characters long",
   *      maxMessage = "Your parameter name cannot be longer than {{ limit }} characters"
   * )
   */
  private $name;

  /**
   * @ORM\Column(type="string", length=255, nullable=true)
   */
  private $value;

  /**
   * @ORM\Column(type="smallint")
   */
  private $paramType = self::TYPE_STRING;

  /**
   * @ORM\Column(type="string", length=80, nullable=true)
   */
  private $comment;


  public function __construct($name = null, $val = null)
  {
    if ($name) {
      $this->setName($name);
      $this->setValue($val);
    }
    return $this;
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

  public function getParamType(): int
  {
    return $this->paramType;
  }

  public function setParamType(int $paramType): self
  {
    $this->paramType = $paramType;

    return $this;
  }

  public function getValueBool(): ?bool
  {
    if ($this->value === null) {
      return null;
    }
    return $this->value == '1' ? true : false;
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
    return DateTime::createFromFormat(static::DATETIMEFORMAT, $this->value);
  }

  public function setValueDateTime(DateTime $val)
  {
    $this->value = $val->format(static::DATETIMEFORMAT);
  }

  public function getValueDate(): ?DateTimeInterface
  {
    if (!$this->value) {
      return null;
    }
    return DateTime::createFromFormat(static::DATEFORMAT, $this->value);
  }

  public function setValueDate(DateTimeInterface $val)
  {
    $this->value = $val->format(static::DATEFORMAT);
  }

  public function formatValue(): ?string
  {
    if ($this->value===null) {
      return null;
    }

    switch ($this->paramType) {
      case self::TYPE_BOOL:
        return $this->getValueBool() ? "true" : "false";
        break;
      default:
        return $this->value;
    }
  }

  public function getTypeText(): string
  {
    if (array_key_exists($this->paramType, self::TYPE_LIST)) {
      return self::TYPE_LIST[$this->paramType];
    }
    return 'n/a';
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
}
