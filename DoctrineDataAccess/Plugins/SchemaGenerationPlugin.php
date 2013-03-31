<?php

namespace DoctrineDataAccess\Plugins;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Tools\SchemaTool;
use Exception;
use Hirudo\Core\Annotations\Import;
use Hirudo\Core\Context\ModulesContext;
use Hirudo\Core\Context\ModulesContext as ModulesContext2;
use Hirudo\Core\Events\Annotations\Listen;
use Symfony\Component\EventDispatcher\Event;

/**
 * Description of SchemaGenerationPlugin
 *
 * @author JeyDotC
 */
class SchemaGenerationPlugin {

    /**
     *
     * @var EntityManager 
     * @Import(id="entity_manager") 
     */
    private $em;

    /**
     *
     * @var ModulesContext2
     */
    private $context;

    function __construct() {
        $this->context = ModulesContext::instance();
    }

    /**
     * Updates the application's schema.
     * 
     * @param Event $e
     * @Listen(to="applicationLoaded")
     */
    function updateSchema(Event $e) {
        //Avoid users different than localhost to execute this plugin.
        if($_SERVER['SERVER_ADDR'] != $_SERVER['REMOTE_ADDR']){
            return;
        }
        
        if ($this->context->getConfig()->get("enviroment") == "prod") {
            trigger_error("Please, deactivate this plugin when working in production enviroment. 
                you can do it at the DoctrineDataccess' manifest.yml file, under the 'plugins:' section.", E_WARNING);
            return;
        }

        $this->context->getDependenciesManager()->resolveDependencies($this);
        
        $schemaTool = new SchemaTool($this->em);
        $metadatas = $this->em->getMetadataFactory()->getAllMetadata();
        $sqls = $schemaTool->getUpdateSchemaSql($metadatas, true);

        if (count($sqls) == 0) {
            return;
        }
        
        $schemaTool->updateSchema($metadatas, true);
    }

}

?>
