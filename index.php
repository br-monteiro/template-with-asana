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
require_once __DIR__ . '/vendor/autoload.php';

try {
    $app = new App\Bootstrap($argv);
    $app->run();
} catch (\Exception $ex) {
    system('echo $(tput setaf 1)' . $ex->getMessage() . '$(tput setaf 0)');
}
