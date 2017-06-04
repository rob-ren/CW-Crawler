<?php
/**
 * Created by PhpStorm.
 * User: robert
 * Date: 18/4/17
 * Time: 3:14 PM
 */

namespace AppBundle\Business;

use Doctrine\Bundle\DoctrineBundle\Registry;
use Doctrine\ORM\QueryBuilder;

class AbstractBusinessModel
{
    protected $doctrine;
    protected $entity;

    /**
     *
     * @param Registry $doctrine
     */
    public function __construct(Registry $doctrine)
    {
        $this->doctrine = $doctrine;
    }

    /**
     *
     * @param string $repo_name
     * @return QueryBuilder
     */
    public function getQueryBuilder($repo_name, $alias)
    {
        $repo = $this->doctrine->getRepository($repo_name);
        $qb = $repo->createQueryBuilder($alias);
        return $qb;
    }

    /**
     *
     * @param string $id
     */
    public function loadById($id)
    {
        return $this->getRepository()->find($id);
    }

    /**
     *
     * @return \Doctrine\Common\Persistence\ObjectRepository
     */
    public function getRepository()
    {
        $reflect = new \ReflectionObject ($this->entity);
        $repo = $this->doctrine->getRepository($reflect->getName());
        return $repo;
    }

    /**
     *
     * @param unknown $entity
     * @return \AppBundle\Business\AbstractBusinessModel
     */
    public function persistEntity($entity)
    {
        $em = $this->doctrine->getManager();
        $em->persist($entity);
        $em->flush();
        return $this;
    }

    /**
     *
     * @param unknown $entity
     * @return \AppBundle\Business\AbstractBusinessModel
     */
    public function removeEntity($entity)
    {
        $em = $this->doctrine->getManager();
        $em->remove($entity);
        $em->flush();
        return $this;
    }
}