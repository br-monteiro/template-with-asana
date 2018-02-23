<?php
namespace App\Helpers;

use App\Config as cfg;
use App\Helpers\FileManager as File;
use App\Bootstrap;

class UpdaterLib
{

    private $stdConfig;
    private $bootstrap;
    private $update = true;
    private $fileName = __DIR__ . cfg::FILE_CONFIG;

    public function __construct(Bootstrap $bootstrap)
    {
        $this->bootstrap = $bootstrap;
        $this->setup()->run();
    }

    private function setup()
    {
        $stdConfig = null;

        if (File::validateFile($this->fileName)) {
            $fileContent = File::readFile($this->fileName);
            $stdConfig = json_decode($fileContent);
        } else {
            $stdConfig = new \stdClass();
        }
        $this->stdConfig = $stdConfig;
        return $this;
    }

    private function run()
    {
        if (isset($this->stdConfig->update)) {
            if (($this->stdConfig->update - time()) <= 0) {
                $this->bootstrap->success("Updating system ...");
                $rootDir = __DIR__ . cfg::DS;
                $resultExecution = system('cd ' . $rootDir . ' && git pull origin master');
                $this->bootstrap->info($resultExecution);
            } else {
                $this->update = false;
            }
        }
        if ($this->update) {
            $this->stdConfig->update = time() + 2592000; // 30 days
            $fileContent = json_encode($this->stdConfig);
            File::createFile($this->fileName, $fileContent);
        }
    }
}
