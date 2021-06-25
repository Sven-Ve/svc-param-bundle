<?php

namespace Svc\ParamBundle\Repository;

use Svc\ParamBundle\Entity\Params;
use DateTime;
use DateTimeInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

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
   * get a param record or null if not exists
   *
   * @param string $name
   * @return Params|null
   */
  private function getEntity(string $name): ?Params
  {
    return $this->findOneBy(['name' => $name]);
  }

  /**
   * private function to create a param record or get it, if exists
   *
   * @param string $name
   * @param integer|null $type
   * @param string|null $comment the comment for the param record, only set during param record creation
   * @return Params
   */
  private function getOrCreateEntity(string $name, ?int $type = Params::TYPE_STRING, ?string $comment = null): Params
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
   * save the param record
   *
   * @param Params $entity
   * @return void
   */
  private function saveEntity(Params $entity)
  {
    $entityManager = $this->getEntityManager();
    $entityManager->persist($entity);
    $entityManager->flush();
  }

  /**
   * set a string parameter
   *
   * @param string $name parameter name
   * @param string $val
   * @param string|null $comment the comment for the param record, only set during param record creation
   * @return void
   */
  public function setParam(string $name, $val, ?string $comment = null)
  {
    $entity = $this->getOrCreateEntity($name, Params::TYPE_STRING, $comment);
    $entity->setValue($val);
    $this->saveEntity($entity);
  }

  /**
   * set a DateTime parameter
   *
   * @param string $name parameter name
   * @param DateTime $val
   * @param string|null $comment the comment for the param record, only set during param record creation
   * @return void
   */
  public function setDateTime(string $name, DateTime $val, ?string $comment = null)
  {
    $entity = $this->getOrCreateEntity($name, Params::TYPE_DATETIME, $comment);
    $entity->setValueDateTime($val);
    $this->saveEntity($entity);
  }

  /**
   * set a Date parameter
   *
   * @param string $name parameter name
   * @param DateTime $val
   * @param string|null $comment the comment for the param record, only set during param record creation
   * @return void
   */
  public function setDate(string $name, DateTimeInterface $val, ?string $comment = null)
  {
    $entity = $this->getOrCreateEntity($name, Params::TYPE_DATE, $comment);
    $entity->setValueDate($val);
    $this->saveEntity($entity);
  }

  /**
   * set a boolean parameter
   *
   * @param string $name parameter name
   * @param DateTime $val
   * @param string|null $comment the comment for the param record, only set during param record creation
   * @return void
   */
  public function setBool(string $name, bool $val, ?string $comment = null)
  {
    $entity = $this->getOrCreateEntity($name, Params::TYPE_BOOL, $comment);
    $entity->setValueBool($val);
    $this->saveEntity($entity);
  }

  /**
   * get a value for a sring param (or null, if not exists)
   *
   * @param string $name parameter name
   * @return string|null return value as string or null if not exists
   */
  public function getParam(string $name): ?string
  {
    $entity = $this->getEntity($name);
    if (!$entity) {
      return null;
    }
    return $entity->getValue();
  }

  /**
   * get a DateTimeParam
   *
   * @param string $name parameter name
   * @return DateTime|null
   */
  public function getDateTime(string $name): ?DateTime
  {
    $entity = $this->getEntity($name);
    if (!$entity) {
      return null;
    }
    return $entity->getValueDateTime();
  }

  /**
   * get a Date param
   *
   * @param string $name parameter name
   * @return DateTimeInterface|null
   */
  public function getDate(string $name): ?DateTimeInterface
  {
    $entity = $this->getEntity($name);
    if (!$entity) {
      return null;
    }
    return $entity->getValueDate();
  }

  /**
   * get a boolean parameter
   *
   * @param string $name
   * @return boolean|null
   */
  public function getBool(string $name): ?bool
  {
    $entity = $this->getEntity($name);
    if (!$entity) {
      return null;
    }
    return $entity->getValueBool();
  }
}
