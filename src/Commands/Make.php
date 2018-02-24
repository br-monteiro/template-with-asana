<?php
namespace App\Commands;

use splitbrain\phpcli\Options;
use App\Bootstrap;
use App\Helpers\FileManager as File;
use App\Config as cfg;
use App\Interfaces\Command;
use App\Helpers\AsanaClient;
use App\Helpers\AsanaTask as task;

class Make implements Command
{

    protected $options;
    protected $bootstrap;
    private $asana;
    private $config;

    public function __construct(Options $options, Bootstrap $bootstrap)
    {
        $this->options = $options;
        $this->bootstrap = $bootstrap;
        $this->config = $this->loadConfig();
        $this->asana = new AsanaClient();
        $this->asana->setToken($this->config->token);
    }

    /**
     * Create a new template and show in console
     */
    public function run()
    {
        $args = $this->validateArgs();
        $taskId = $this->getTaskId($args['link']);
        $isMergeRequest = $this->options->getOpt('mr') ? true : false;
        $templateContent = $this->loadTemplate($args['template']);
        $taskProperties = $this->asana->getClient()->get('/tasks/' . $taskId, null);
        task::setProperties($taskProperties);

        // create a template
        $templateContent = $this->writeLink($templateContent, $args['link']);
        $templateContent = $this->writeDescription($templateContent, task::getDescription());
        $templateContent = $this->writeTags($templateContent, task::getTags());
        $templateContent = $this->writeFollowers($templateContent, task::getFollowers());
        //
        $solution = [];
        $this->bootstrap->notice('Describe the solution used for this task');
        $this->bootstrap->info('Use \ok! to finish editing');
        $this->entryText($solution);
        $templateContent = $this->writeSolution($templateContent, implode("\n", $solution));
        //
        $observation = [];
        $this->bootstrap->notice('Do you have any comments?');
        $this->bootstrap->info('Use \ok! to finish editing');
        $this->entryText($observation);
        $observation = count($observation) > 0 ? $observation : ['* `none`'];
        $templateContent = $this->writeObservation($templateContent, implode("\n", $observation));
        //
        $hashTags = [];
        $this->bootstrap->notice('Enter some important words for this task (One per line)');
        $this->bootstrap->info('Use \ok! to finish editing');
        $this->entryText($hashTags);
        $templateContent = $this->writeHashTags($templateContent, $hashTags, $isMergeRequest);

        echo str_repeat('-', 30) . "\n";
        echo $templateContent;
        echo "\n" . str_repeat('-', 30) . "\n";
    }

    /**
     * Validate input args
     *
     * @return array
     */
    private function validateArgs(): array
    {
        $arrResult = ['template', 'link'];
        $args = $this->options->getArgs();

        if (count($args) > 2 || empty($args)) {
            $this->bootstrap->info("Run: asana --help");
            $this->bootstrap->fatal("Hum... something went wrong. Check the correct command syntax.");
        }

        $template = count($args) > 1 ? $args[0] : 'template';
        $link = count($args) > 1 ? $args[1] : $args[0];

        if (preg_match('/^http(s)?:\/{2}app\.asana\.com\/.*$/', $link)) {
            $arrResult = [
                'template' => $template,
                'link' => $link
            ];
        } else {
            $this->bootstrap->info("Run: asana --help");
            $this->bootstrap->fatal("The Task URL is not valid! =(");
        }

        return $arrResult;
    }

    /**
     * Return the task ID
     *
     * @param string $link
     * @return int
     */
    private function getTaskId(string $link)
    {
        $matches = [];
        preg_match('/\d+$/', $link, $matches);
        return $matches[0] ?? null;
    }

    /**
     * Load the configurations of user
     *
     * @return \stdClass
     */
    private function loadConfig(): \stdClass
    {
        $fileName = __DIR__ . cfg::FILE_CONFIG;

        if (File::validateFile($fileName)) {
            $fileContent = File::readFile($fileName);
            return json_decode($fileContent);
        } else {
            $this->bootstrap->critical("Could not find the config file");
            $this->bootstrap->info("Path: " . $fileName);
            exit;
        }
    }

    /**
     * Load the template layout file
     *
     * @param string $template
     * @return string
     */
    private function loadTemplate(string $template)
    {
        $template = str_replace('/src/Commands/', '/templates/', __DIR__ . cfg::DS . $template . ".md");
        if (!File::validateFile($template)) {
            $this->bootstrap->critical("Could not find the indicated template");
            $this->bootstrap->info("Path: " . $template);
            exit;
        }

        return File::readFile($template);
    }

    /**
     * Change the task link into template
     *
     * @param string $templateContent
     * @param string $link
     * @return string
     */
    private function writeLink(string $templateContent, string $link): string
    {
        return str_replace(cfg::LINK, $link, $templateContent);
    }

    /**
     * Change the tags into template
     *
     * @param string $templateContent
     * @param array $tags
     * @return string
     */
    private function writeTags(string $templateContent, array $tags): string
    {
        $value = [];
        foreach ($tags as $name) {
            $value[] = '`' . $name . '`';
        }
        $tags = implode(', ', $value);
        return str_replace(cfg::TAG, $tags, $templateContent);
    }

    /**
     * Change the description into template
     *
     * @param string $templateContent
     * @param string $description
     * @return string
     */
    private function writeDescription(string $templateContent, string $description): string
    {
        return str_replace(cfg::DESCRIPTION, $description, $templateContent);
    }

    /**
     * Change the Followers into template
     *
     * @param string $templateContent
     * @param array $followers
     * @return string
     */
    private function writeFollowers(string $templateContent, array $followers): string
    {
        $value = [];
        foreach ($followers as $name) {
            $value[] = '`' . $name . '`';
        }
        $followers = implode(', ', $value);
        return str_replace(cfg::FOLLOWER, $followers, $templateContent);
    }

    /**
     * Change the HashTags into template
     *
     * @param string $templateContent
     * @param array $hashTags
     * @param bool $isMergeRequest
     * @return string
     */
    private function writeHashTags(string $templateContent, array $hashTags, bool $isMergeRequest = false): string
    {
        $value = [];
        $template = $isMergeRequest ? cfg::MR : cfg::PR;

        foreach ($hashTags as $name) {
            $templateLike = $template;
            $templateLike = str_replace(cfg::TAG, $name, $templateLike);
            $value[] = '[' . $name . '](' . $templateLike . ')';
        }
        if (count($value) > 0) {
            $hashTags = implode(', ', $value);
            return str_replace(cfg::HASHTAG, $hashTags, $templateContent);
        }

        return '`none`';
    }

    /**
     * Change the Solution into template
     *
     * @param string $templateContent
     * @param string $solution
     * @return string
     */
    private function writeSolution(string $templateContent, string $solution): string
    {
        return str_replace(cfg::SOLUTION, $solution, $templateContent);
    }

    /**
     * Change the Observation into template
     *
     * @param string $templateContent
     * @param string $solution
     * @return string
     */
    private function writeObservation(string $templateContent, string $observation): string
    {
        return str_replace(cfg::OBSERVATION, $observation, $templateContent);
    }

    /**
     * Capture user input
     *
     * @param array $text
     * @return type
     */
    private function entryText(array &$text)
    {
        $handle = fopen("php://stdin", "r");
        $line = fgets($handle);
        $value = trim($line);
        $text[] = $value;
        if ($value == '\ok!') {
            fclose($handle);
            array_pop($text);
            return;
        }
        fclose($handle);
        $this->entryText($text);
    }
}
