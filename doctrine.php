<?php

ini_set("error_reporting", E_ERROR & ~E_WARNING & ~E_NOTICE);
/*
 * CLI tool configured to work on Hirudo.
 */
require_once "../../../init.php";

new Hirudo\Core\ModulesManager();

Hirudo\Core\ModulesManager::getAutoLoader()->registerNamespaces(array(
    "Symfony\Component\Console" => dirname(__FILE__) . DS . "symfony-console",
));

$app = "";

if ($argc >= 2) {
    $app = $_SERVER['argv'][$argc - 1];
    if (strpos($app, ":") === 0) {
        $app = ltrim($app, ":");
        unset($_SERVER['argv'][$argc - 1]);
    } else {
        $app = "";
    }
}

if (!empty($app)) {
    Hirudo\Core\Context\ModulesContext::instance()->getConfig()->loadApp($app);
}

$helperSet = new \Symfony\Component\Console\Helper\HelperSet(
                array(
                    "em" => new \Doctrine\ORM\Tools\Console\Helper\EntityManagerHelper(\DoctrineDataAccess\Services\EntityManagerProvider::getEntityManager()),
                )
);

\Doctrine\ORM\Tools\Console\ConsoleRunner::run($helperSet);
?>
