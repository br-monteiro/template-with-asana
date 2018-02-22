<?php
namespace App\Helpers;

class FileManager
{

    /**
     * Display a message
     *
     * @param string $msg
     */
    public static function msg($msg)
    {
        print($msg . "\n");
    }

    /**
     * Verify if the file exists
     *
     * @param string $fileName
     * @param bool $throwException
     * @return boolean
     * @throws \Exception
     */
    public static function validateFile(string $fileName, bool $throwException = false): bool
    {
        if (!file_exists($fileName)) {
            if ($throwException) {
                throw new \Exception("[ERROR] Arquivo não encontrado!");
            }
            return false;
        }
        return true;
    }

    /**
     * Create or update a file
     *
     * @param string $fileName
     * @param string $fileContent
     * @param string $fileMode
     * @return bool
     */
    public static function createFile(string $fileName, string $fileContent = '', string $fileMode = 'w+'): bool
    {
        if (file_exists($fileName) && !is_writable($fileName)) {
            self::msg("[ERROR] Arquivo sem permissão de escrita.");
            return false;
        }
        $file = fopen($fileName, $fileMode);
        if (!$file) {
            return false;
        }
        if (fwrite($file, $fileContent) === false) {
            fclose($file);
            return false;
        }
        if (!fclose($file)) {
            return false;
        }
        return true;
    }

    /**
     * Read the file content
     *
     * @param string $fileName
     * @param bool $returnAsArray
     * @return mixed
     */
    public static function readFile(string $fileName, bool $returnAsArray = false)
    {
        if (!self::validateFile($fileName)) {
            return false;
        }

        if ($returnAsArray === true) {
            return file($fileName);
        }

        return file_get_contents($fileName);
    }
}
