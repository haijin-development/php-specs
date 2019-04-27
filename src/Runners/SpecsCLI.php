<?php
declare(strict_types=1);

namespace Haijin\Specs\Runners;

use Clue\Commander\Router;
use Haijin\Specs\ReportRenderers\ConsoleReportRenderer;
use Haijin\Specs\Specs\SpecContextDefinitions;
use function getcwd;

/**
 * The command line interface to the specs runner.
 *
 * @package Haijin\Specs\Runners
 */
class SpecsCLI
{
    /**
     * @var string The directory containing the specs subdirectory and other files required by the specs.
     */
    protected $testsPath;
    /**
     * @var string The folder containing the spec files to run.
     */
    protected $specsFolder;
    /**
     * @var ConsoleReportRenderer The renderer of the results collected during the specs evaluation.
     */
    protected $reportRenderer;

    /// Initializing

    /**
     * SpecsCLI constructor.
     */
    public function __construct($testsPath, $specsFolderName)
    {
        $this->testsPath = $testsPath;
        $this->specsFolderName = $specsFolderName;
        $this->reportRenderer = new ConsoleReportRenderer();
    }

    /// Accessing

    /**
     * Evaluates the command line command.
     * The accepted commands are:
     *      - specs init
     *      - specs [file[:line]|directory]
     *      - specs help|-h|--help
     *
     * If the evaluation of the command ends successfully ends the script with exit(0).
     * If there is an error during the execution of a commands ends the script with exit(1).
     */
    public function evaluate(): void
    {
        $router = new Router();

        $router->add('help|-h|--help', function ($args) {

            $this->runHelpCommand();

        });

        $router->add('init', function () {
            $this->runSpecsInitCommand();
        });

        $router->add('[<file>]', function ($args) {

            $file = isset($args['file']) ? $args['file'] : null;

            $this->runSpecsCommand($file);

        });

        $router->execArgv();

        exit(0);
    }

    /**
     * Executes the help command.
     * Show help information about these commands.
     */
    public function runHelpCommand()
    {
        echo 'Usage:';
        echo "\n";
        echo "\n";
        echo '  php ./vendor/bin/specs help|--help|-h';
        echo "\n";
        echo '    Show this help message.';
        echo "\n";
        echo "\n";
        echo '  php ./vendor/bin/specs init';
        echo "\n";
        echo '    Creates the initial directories setup for the specs if it does not exists.';
        echo "\n";
        echo "\n";
        echo '  php ./vendor/bin/specs [directory|filename[:line]]';
        echo "\n";
        echo '    If no filename or directory is given runs all the specs files located in the directory tests/specs/.';
        echo "\n";
        echo '    Example:';
        echo "\n";
        echo '      php ./vendor/bin/specs';
        echo "\n";
        echo "\n";
        echo '    If a directory is given runs all the specs located in that directory.';
        echo "\n";
        echo '    Example:';
        echo "\n";
        echo '      php ./vendor/bin/specs tests/specs/beforeAndAfterClosures';
        echo "\n";
        echo "\n";
        echo '    If a filename is given runs all the specs located in that filename.';
        echo "\n";
        echo '    Example:';
        echo "\n";
        echo '      php ./vendor/bin/specs tests/specs/beforeAndAfterClosures/evaluatingBeforeAndAfterClosures.php';
        echo "\n";
        echo "\n";
        echo '    If a filename:line is given runs the single spec located at the given line number of that filename.';
        echo "\n";
        echo '    Example:';
        echo "\n";
        echo '      php ./vendor/bin/specs tests/specs/beforeAndAfterClosures/evaluatingBeforeAndAfterClosures.php:48';
        echo "\n";
    }

    /**
     * Executes the init command.
     * The init command sets up the directory for the specs. It
     *      - creates a directory named tests/ if it does not exist
     *      - creates a file named tests/specsBoot.php if it does not exist
     *      - creates a directory named tests/specs if it does not exists
     *          with a sample file named tests/specs/specExample
     */
    public function runSpecsInitCommand(): void
    {
        if (!$this->existsSpecsFolder()) {

            $this->createSpecsFolder();

            echo "Created 'tests/specs/' folder in {$this->specsDirectory()}.\n";

        } else {

            echo "'tests/specs/' folder not created because it already exists in {$this->specsDirectory()}.\n";

        }

        if (!file_exists($this->specsBootFile())) {

            $this->createSpecsBootFile();

            echo "Created 'specsBoot.php' in {$this->specsBootFile()}.\n";

        } else {

            echo "'specsBoot.php' not created because it already exists in {$this->specsBootFile()}.\n";

        }
    }

    /// Command line interface

    /**
     * Returns true if the specs/ directory exists, false otherwise.
     * The specs/ directory is the subdirectory of the tests/ directory where all the spec.php files are located.
     * @return bool True if the directory tests/specs/ exists, false otherwise.
     */
    protected function existsSpecsFolder(): bool
    {
        return file_exists($this->specsDirectory());
    }

    /**
     * Returns the path to the tests/specs/ directory.
     *
     * @return string The path to the specs directory.
     */
    protected function specsDirectory()
    {
        return $this->testsPath . $this->specsFolderName . "/";
    }

    /**
     * If the directory tests/ does not exist creates it.
     * If the file tests/specsBoot.php does not exists creates one from a specsBoot.php template.
     * If the directory tests/specs/ does not exists creates it and leaves a template of a spec.php file in it.
     */
    protected function createSpecsFolder(): void
    {
        if (!file_exists($this->testsPath)) {
            mkdir($this->testsPath);
        }

        if (!file_exists($this->specsDirectory())) {
            mkdir($this->specsDirectory());
        }

        if (!file_exists($this->specsDirectory() . "specExample.php")) {
            copy(
                __DIR__ . "/../../initCommandTemplates/specExample.php",
                $this->specsDirectory() . "specExample.php"
            );
        }
    }

    /// Running init command

    /**
     * Returns the path to the file tests/specsBoot.php
     *
     * @return string The path to the file tests/specsBoot.php.
     */
    protected function specsBootFile(): string
    {
        return $this->testsPath . "specsBoot.php";
    }

    /**
     * Creates the tests/specsBoot.php file from a template file.
     */
    protected function createSpecsBootFile(): void
    {
        copy(
            __DIR__ . "/../../initCommandTemplates/specsBoot.php",
            $this->specsBootFile()
        );
    }

    /// Running specs command

    /**
     * Runs all the specs located in the directory or file named $folderOrFile.
     * The give $folderOrFile string can be any of:
     *      - a directory_path
     *      - a file_path
     *      - a file_path:line_number
     *
     * If there directory or file does not exist or if there is an error during the evaluation of the specs
     * the script terminates with exit(1).
     * Otherwise shows the report about the specs run and terminates with exit(0).
     *
     * @param string $folderOrFile The directory, file or file:line to read specs from and evaluate.
     */
    public function runSpecsCommand(?string $directoryOrFile): void
    {
        if ($directoryOrFile === null) {
            $directoryOrFile = $this->specsDirectory();
        }


        $fileParts = explode(":", $directoryOrFile);

        if (isset($fileParts[1])) {

            $file = $fileParts[0];
            $lineNumber = (int) $fileParts[1];

        } else {

            $file = $fileParts[0];
            $lineNumber = null;

        }

        if (!is_dir($file) && !file_exists($file)) {
            echo "Invalid file '{$file}'.\n";
            exit(1);
        }

        $specsRunner = $this->specsRunner();

        $specsContextDefinitions = $this->loadSpecsBootFile();

        $specsRunner->runOn($file, $lineNumber, $specsContextDefinitions);

        $this->reportRenderer->renderReportFrom($specsRunner);

        if ($specsRunner->getStatistics()->invalidExpectationsCount() != 0) {
            exit(1);
        }
    }

    /**
     * Creates, configures and returns a new SpecsRunner.
     *
     * @return SpecsRunner The created SpecsRunner.
     */
    protected function specsRunner(): SpecsRunner
    {
        $specsRunner = new SpecsRunner();

        $specsRunner->setOnSpecRunCallable(function ($spec, $status) {

            $this->reportRenderer->renderFeedbackOfSpecStatus($spec, $status);

        });

        return $specsRunner;
    }

    /**
     * If the tests/specsBoot.php file exists reads it and returns the SpecContextDefinitions configured in that file.
     * Otherwise returns an empty SpecContextDefinitions.
     */
    protected function loadSpecsBootFile(): SpecContextDefinitions
    {
        $specsBootFile = $this->specsBootFile();

        return SpecsGlobalContextConfiguration::configure(function ($specs) use ($specsBootFile) {
            if (file_exists($specsBootFile)) {
                require_once($specsBootFile);
            }
        });
    }

}