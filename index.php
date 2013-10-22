<?php
/*
 * Booker
 * index.php
 *
 */

require_once 'conf/config.php';
session_start();
$_SESSION['language'] = DEF_LANGUAGE;

function __autoload($class)
{
    require_once 'lib'.SYS_SEPARATOR.$class.'.php';
}
//Deb::p($_POST);
//Deb::p($_COOKIE);
//Deb::p($_SESSION);
try
{
    $constr = new Controller();

    print $constr->printPage();
}
catch(Exception $ex)
{
    print $ex->getMessage().'<br>';
}
?>
