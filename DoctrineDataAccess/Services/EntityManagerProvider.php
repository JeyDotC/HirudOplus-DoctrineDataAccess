<?php

namespace DoctrineDataAccess\Services;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Tools\Setup;
use Hirudo\Core\Annotations\Export;
use Hirudo\Core\Context\ModulesContext;
use Hirudo\Lang\Loader;
use Hirudo\Lang\DirectoryHelper;

/**
 * Creates and configures an EntityManager.
 *
 * @author JeyDotC
 * 
 * @Export(id="entity_manager", factory="getEntityManager")
 */
class EntityManagerProvider {

    /**
     *
     * @var EntityManagerProvider
     */
    private static $instance;

    public static function getEntityManager() {

        if (!isset(self::$instance)) {
            self::$instance = new EntityManagerProvider();
            self::$instance->init();
        }

        return self::$instance->em;
    }

    /**
     *
     * @var EntityManager 
     */
    private $em;
    private $businessRoot;

    /**
     *
     * @var \Hirudo\Core\Context\AppConfig
     */
    private $config;

    function __construct() {
        $this->config = ModulesContext::instance()->getConfig();
        $businessRoot = $this->config->get("businessRoot", "src");
        $this->businessRoot = Loader::toSinglePath($businessRoot, "");
    }

    protected function init() {
        $config = $this->generateConfiguration();

        $this->em = EntityManager::create($this->config->get("dbal", array()), $config);
    }

    private function generateConfiguration() {
        $dir = new \RecursiveDirectoryIterator($this->businessRoot);
        $directories = array();
        $aliases = array();

        while ($dir->valid()) {
            if (!$dir->isDot() && $dir->isDir()) {
                $directories[] = $dir->getPathname() . DS . "Models" . DS . "Entities";
                $aliases[$dir->getBasename()] = "{$dir->getBasename()}\\Models\\Entities";
            }
            $dir->next();
        }
        
        $config = Setup::createAnnotationMetadataConfiguration($directories, $this->config->get("debug"));
        
        foreach ($aliases as $alieas => $namespace) {
            $config->addEntityNamespace($alieas, $namespace);
        }
        
        return $config;
    }

}

?>
