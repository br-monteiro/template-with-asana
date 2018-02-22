<?php
namespace App\Commands;

use splitbrain\phpcli\Options;
use App\Bootstrap;
use App\Helpers\FileManager as File;
use App\Config as cfg;
use App\Interfaces\Command;

class Hello implements Command
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
        $fileName = __DIR__ . cfg::FILE_CONFIG;
        $tokenExists = false;

        if (File::validateFile($fileName)) {
            $fileContent = File::readFile($fileName);
            $stdToken = json_decode($fileContent);
            $tokenExists = isset($stdToken->token) ?: false;
        }

        $this->bootstrap->success("Bem-vindo! Aparentemente tudo está ok.");
        if (!$tokenExists) {
            $this->bootstrap->info("Agora é preciso gerar um token pessoal.\n"
                . "Você pode gerar um token seguindo esta Doc.:\n"
                . "https://asana.com/pt/guide/help/api/api" . "\n");
        }

        $this->bootstrap->info("Você pode registrar ou alterar o token com o comando");
        print "asana token <token>\n";
    }
}
