<?php

namespace DoctrineDataAccess\Models\Components;

use DoctrineDataAccess\Services\EntityManagerProvider;
use Doctrine\ORM\EntityManager;
use Hirudo\Core\Annotations\Import;

/**
 * Description of DoctrineComponent
 *
 * @author JeyDotC
 */
class DoctrineComponent {

    /**
     * @var EntityManagerProvider
     */
    private $entityManagerProvider;

    /**
     *
     * @var EntityManager
     */
    protected $em;

    /**
     * 
     * @param \DoctrineDataAccess\Services\EntityManagerProvider $entityManagerProvider
     * 
     * @Import(id="entity_manager_provider")
     */
    public function setEntityManagerProvider(EntityManagerProvider $entityManagerProvider) {
        $this->entityManagerProvider = $entityManagerProvider;
        $this->em = $this->entityManagerProvider->getEntityManager();
    }

}

?>
