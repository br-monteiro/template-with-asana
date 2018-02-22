#!/usr/bin/php
<?php
/**
 * This is a simple library for generating MR and PR templates using the Asana API.
 * 
 * LAUS DEO .'.
 * 
 * @author Edson B S Monteiro <bruno.monteirodg@gmail.com>
 */
// include the composer autoload
require_once 'vendor/autoload.php';

$app = new App\Bootstrap($argv);
$app->run();
