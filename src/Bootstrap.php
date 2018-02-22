<?php
namespace App;

use splitbrain\phpcli\CLI;
use splitbrain\phpcli\Options;

class Bootstrap extends CLI
{

    protected $argv;
    protected $cliParams;
    private $className = '\App\Commands\\';

    public function __construct(array $argv, $autocatch = true)
    {
        $this->argv = $argv;
        parent::__construct($autocatch);
    }

    protected function setup(Options $options)
    {
        $options->setHelp('A simple wizard to create MR and PR');
        // token command
        $options->registerCommand('token', 'Set the personal token');
        $options->registerArgument('value', 'The value of the personal access token', true, 'token');
        // make command
        $options->registerCommand('make', 'Create a new description for MR or PR');
        $options->registerArgument('template', 'Indicates which template will be used (filename without extension)', false, 'make');
        $options->registerArgument('task-link', 'Indicates the URL of Asana\'s task', true, 'make');
        $options->registerOption('mr', 'Indicates the use of Merge Request (Gitlab)', 'm', false, 'make');
    }

    protected function main(Options $options)
    {
        if ($options->getCmd()) {
            $commandName = ucfirst($options->getCmd());
            $className = $this->className . $commandName;
            if (class_exists($className)) {
                $command = new $className($options, $this);
                $command->run();
                return;
            }
        }
        echo $options->help();
    }
}
