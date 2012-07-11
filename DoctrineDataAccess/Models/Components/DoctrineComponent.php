<?php

namespace DoctrineDataAccess\Models\Components;

use Hirudo\Core\Annotations\Import;
use Doctrine\Common\Persistence\ObjectRepository;

/**
 * Description of DoctrineComponent
 *
 * @author JeyDotC
 */
abstract class DoctrineComponent implements ObjectRepository {

    private $className;

    /**
     *
     * @var \Doctrine\ORM\EntityManager
     * 
     * @Import(id="entity_manager")
     */
    protected $em;

    function __construct($className = "") {
        $this->className = $className;
    }

    public function find($id) {
        return $this->getRepository()->find($id);
    }

    public function findAll() {
        return $this->getRepository()->findAll();
    }

    public function findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null) {
        return $this->getRepository()->findBy($criteria, $orderBy, $limit, $offset);
    }

    public function findOneBy(array $criteria) {
        return $this->getRepository()->findOneBy($criteria);
    }

    public function getClassName() {
        return $this->className;
    }

    public function setClassName($className) {
        $this->className = $className;
    }

    /**
     * 
     * @return \Doctrine\ORM\EntityRepository
     */
    private function getRepository() {
        if (empty($this->className)) {
            throw new \LogicException("You have not set a class name, you can give the class name by calling the parent's constructor of this class, or simply by calling setClassName.");
        }
        return $this->em->getRepository($this->className);
    }

    /**
     * Create a new QueryBuilder instance that is prepopulated for this entity name
     *
     * @param string $alias
     * @return \Doctrine\DBAL\Query\QueryBuilder
     */
    public function createQueryBuilder($alias) {
        return $this->getRepository()->createQueryBuilder($alias);
    }

    /**
     * Create a new Query instance based on a predefined metadata named query.
     *
     * @param string $queryName
     * @return \Doctrine\ORM\Query
     */
    public function createNamedQuery($queryName) {
        return $this->getRepository()->createNamedQuery($queryName);
    }

    /**
     * Clears the repository, causing all managed entities to become detached.
     */
    public function clear() {
        $this->getRepository()->clear();
    }

    /**
     * Adds support for magic finders.
     *
     * @return array|object The found entity/entities.
     * @throws BadMethodCallException  If the method called is an invalid find* method
     *                                 or no find* method at all and therefore an invalid
     *                                 method call.
     */
    public function __call($method, $arguments) {
        call_user_method_array($method, $this->getRepository(), $arguments);
    }

}

?>
