<?php

ini_set("error_reporting", E_ERROR & ~E_WARNING & ~E_NOTICE);
/*
 * CLI tool configured to work on Hirudo.
 */
require_once "../../../init.php";

$manager = new Hirudo\Core\ModulesManager("drupal");

Hirudo\Core\ModulesManager::getAutoLoader()->registerNamespaces(array(
    "Doctrine" => dirname(__FILE__) . DS . "doctrine",
    "Symfony\Component\Console" => dirname(__FILE__) . DS . "doctrine" . DS . "Doctrine",
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
    $manager->prepareApplication($app);
}

$helperSet = new \Symfony\Component\Console\Helper\HelperSet(
                array(
                    "em" => new \Doctrine\ORM\Tools\Console\Helper\EntityManagerHelper(\DoctrineDataAccess\Services\EntityManagerProvider::getEntityManager()),
                )
);

\Doctrine\ORM\Tools\Console\ConsoleRunner::run($helperSet);
?>
