<?php

namespace Svc\ParamBundle\Repository;

use DateTime;
use DateTimeInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Svc\ParamBundle\Entity\Params;
use Svc\ParamBundle\Enum\ParamType;

/**
 * @method Params|null find($id, $lockMode = null, $lockVersion = null)
 * @method Params|null findOneBy(array $criteria, array $orderBy = null)
 * @method Params[]    findAll()
 * @method Params[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ParamsRepository extends ServiceEntityRepository
{
  public function __construct(ManagerRegistry $registry)
  {
    parent::__construct($registry, Params::class);
  }

  /**
   * get a param record or null if not exists.
   */
  private function getEntity(string $name): ?Params
  {
    return $this->findOneBy(['name' => $name]);
  }

  /**
   * private function to create a param record or get it, if exists.
   *
   * @param string|null $comment the comment for the param record, only set during param record creation
   */
  private function getOrCreateEntity(string $name, ?ParamType $type = ParamType::STRING, ?string $comment = null): Params
  {
    $entity = $this->getEntity($name);
    if ($entity) {
      return $entity;
    }
    $entity = new Params($name);
    $entity->setParamType($type);
    $entity->setComment($comment);

    return $entity;
  }

  /**
   * save the param record.
   */
  private function saveEntity(Params $entity): void
  {
    $entityManager = $this->getEntityManager();
    $entityManager->persist($entity);
    $entityManager->flush();
  }

  /**
   * set a string parameter.
   *
   * @param string      $name    parameter name
   * @param string|null $comment the comment for the param record, only set during param record creation
   */
  public function setParam(string $name, string $val, ?string $comment = null): void
  {
    $entity = $this->getOrCreateEntity($name, ParamType::STRING, $comment);
    $entity->setValue($val);
    $this->saveEntity($entity);
  }

  /**
   * set a DateTime parameter.
   *
   * @param string      $name    parameter name
   * @param string|null $comment the comment for the param record, only set during param record creation
   */
  public function setDateTime(string $name, DateTime $val, ?string $comment = null): void
  {
    $entity = $this->getOrCreateEntity($name, ParamType::DATETIME, $comment);
    $entity->setValueDateTime($val);
    $this->saveEntity($entity);
  }

  /**
   * set a Date parameter.
   *
   * @param string      $name    parameter name
   * @param DateTime    $val
   * @param string|null $comment the comment for the param record, only set during param record creation
   */
  public function setDate(string $name, DateTimeInterface $val, ?string $comment = null): void
  {
    $entity = $this->getOrCreateEntity($name, ParamType::DATE, $comment);
    $entity->setValueDate($val);
    $this->saveEntity($entity);
  }

  /**
   * set a boolean parameter.
   *
   * @param string      $name    parameter name
   * @param string|null $comment the comment for the param record, only set during param record creation
   */
  public function setBool(string $name, bool $val, ?string $comment = null): void
  {
    $entity = $this->getOrCreateEntity($name, ParamType::BOOL, $comment);
    $entity->setValueBool($val);
    $this->saveEntity($entity);
  }

  /**
   * set an integer parameter.
   *
   * @param string $name parameter name
   * @param int    $val  the integer value
   */
  public function setInteger(string $name, int $val, ?string $comment = null): void
  {
    $entity = $this->getOrCreateEntity($name, ParamType::INTEGER, $comment);
    $entity->setValue((string) $val);
    $this->saveEntity($entity);
  }

  /**
   * get a value for a string param (or null, if not exists).
   *
   * @param string $name parameter name
   *
   * @return string|null return value as string or null if not exists
   */
  public function getParam(string $name): ?string
  {
    $entity = $this->getEntity($name);

    return $entity?->getValue();
  }

  /**
   * get a DateTimeParam.
   *
   * @param string $name parameter name
   */
  public function getDateTime(string $name): ?DateTime
  {
    $entity = $this->getEntity($name);

    return $entity?->getValueDateTime();
  }

  /**
   * get a Date param.
   *
   * @param string $name parameter name
   */
  public function getDate(string $name): ?DateTimeInterface
  {
    $entity = $this->getEntity($name);

    return $entity?->getValueDate();
  }

  /**
   * get a boolean parameter.
   */
  public function getBool(string $name): ?bool
  {
    $entity = $this->getEntity($name);

    return $entity?->getValueBool();
  }

  /**
   * get a integer parameter.
   */
  public function getInteger(string $name, ?int $default = null): ?int
  {
    $entity = $this->getEntity($name);
    if (!$entity) {
      return $default;
    }

    return (int) $entity->getValue();
  }
}
