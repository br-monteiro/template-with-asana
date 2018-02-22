<?php
namespace App\Interfaces;

use splitbrain\phpcli\Options;
use App\Bootstrap;

/**
 * Interface of Commands
 *
 * LAUS DEO .'.
 * 
 * @author Edson B S Monteiro <bruno.monteirodg@gmail.com>
 */
interface Command
{

    public function __construct(Options $options, Bootstrap $bootstrap);

    public function run();
}
