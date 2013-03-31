<?php

namespace DoctrineDataAccess\Services;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Tools\Setup;
use Hirudo\Core\Annotations\Export;
use Hirudo\Core\Context\ModulesContext;
use Hirudo\Lang\Loader;

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
        $dbal = $this->config->get("dbal");
        $this->em = EntityManager::create($dbal, $config);
    }

    private function generateConfiguration() {
        $dir = new \RecursiveDirectoryIterator($this->businessRoot, \FilesystemIterator::SKIP_DOTS);
        $directories = array();
        $aliases = array();

        while ($dir->valid()) {
            if ($dir->isDir()) {
                $directories[] = $dir->getPathname() . DS . "Models" . DS . "Entities";
                $aliases[$dir->getBasename()] = "{$dir->getBasename()}\\Models\\Entities";
            }
            $dir->next();
        }

        $metadataImpl = $this->config->get("metadata_configuration_class");

        $config = Setup::createConfiguration($this->config->get("enviroment") == "dev");
        if ($metadataImpl != null) {
            $config->setMetadataDriverImpl(new $metadataImpl($directories));
        } else {
            $config->setMetadataDriverImpl($config->newDefaultAnnotationDriver($directories, true));
        }
        foreach ($aliases as $alias => $namespace) {
            $config->addEntityNamespace($alias, $namespace);
        }

        return $config;
    }

}

?>
