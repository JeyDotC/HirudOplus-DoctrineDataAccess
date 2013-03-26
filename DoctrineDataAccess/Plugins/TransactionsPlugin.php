<?php

namespace DoctrineDataAccess\Plugins;

use Doctrine\ORM\EntityManager;
use Hirudo\Core\Annotations\Import;
use Hirudo\Core\Context\ModulesContext;
use Hirudo\Core\Events\AfterTaskEvent;
use Hirudo\Core\Events\Annotations\Listen;
use Hirudo\Core\Events\BeforeTaskEvent;

/**
 * Description of TransactionsPlugin
 *
 * @author JeyDotC
 */
class TransactionsPlugin {

    /**
     *
     * @var EntityManager
     * @Import(id="entity_manager")
     */
    private $em;
    private $transactionBegun = false;

    /**
     * 
     * @param BeforeTaskEvent $e
     * 
     * @Listen(to="beforeTask")
     */
    function beginTransaction(BeforeTaskEvent $e) {
        $transactionAnnotation = $e->getTask()->getTaskAnnotation("DoctrineDataAccess\\Annotations\\Transaction");
        $this->transactionBegun = $transactionAnnotation != null;
        
        if ($this->transactionBegun && !isset($this->em)) {
            ModulesContext::instance()->getDependenciesManager()->resolveDependencies($this);
        }
    }

    /**
     * 
     * @param AfterTaskEvent $e
     * 
     * @Listen(to="afterTask")
     */
    function endTransaction(AfterTaskEvent $e) {
        if ($this->transactionBegun) {
            $this->em->flush();
        }
    }

}

?>
