<?php
namespace App\Commands;

use splitbrain\phpcli\Options;
use App\Bootstrap;
use App\Helpers\FileManager as File;
use App\Config as cfg;
use App\Interfaces\Command;

class Token implements Command
{

    protected $options;
    protected $bootstrap;

    public function __construct(Options $options, Bootstrap $bootstrap)
    {
        $this->options = $options;
        $this->bootstrap = $bootstrap;
    }

    public function run()
    {
        $tokenValue = $this->validateArgs();
        $fileName = __DIR__ . cfg::FILE_CONFIG ?: '';
        $stdToken = null;

        if (File::validateFile($fileName)) {
            $fileContent = File::readFile($fileName);
            $stdToken = json_decode($fileContent);
        } else {
            $stdToken = new \stdClass();
        }

        $stdToken->token = $tokenValue;
        $fileContent = json_encode($stdToken);

        if (File::createFile($fileName, $fileContent)) {
            $this->bootstrap->success("Token successfully set");
        } else {
            $this->bootstrap->error("Failed to save the token");
        }
    }

    /**
     * Validate Args
     * @return string Token value
     */
    private function validateArgs()
    {
        $args = $this->options->getArgs();

        if (count($args) > 1) {
            $this->bootstrap->info("Run: asana --help");
            $this->bootstrap->fatal("Hum... something went wrong. Check the correct command syntax.");
        }

        return $args[0];
    }
}
