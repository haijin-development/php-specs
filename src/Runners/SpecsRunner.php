<?php
declare(strict_types=1);

namespace Haijin\Specs\Runners;

use Haijin\Specs\Evaluator\SpecEvaluator;
use Haijin\Specs\Evaluator\SpecsStatistics;
use Haijin\Specs\Specs\SpecContextDefinitions;
use Haijin\Specs\Specs\SpecDescription;

/**
 * Collects Specs to evaluate from a directory of a file and evaluates them.
 *
 * @package Haijin\Specs\Runners
 */
class SpecsRunner
{
    /**
     * @var SpecEvaluator The evaluator of Specs. The SpecsRunner does the recollection of Spec from files
     *  and delegates the evaluation to this object.
     */
    protected $specsEvaluator;

    /**
     * SpecsRunner constructor.
     */
    public function __construct()
    {
        $this->specsEvaluator = new SpecEvaluator();
    }

    /**
     * Returns the SpecsStatistics collected during the evaluation of the specs.
     * This statistics can be used to render a report on the specs run.
     *
     * @return SpecsStatistics The SpecsStatistics collected during the evaluation of the specs.
     */
    public function getStatistics(): SpecsStatistics
    {
        return $this->specsEvaluator->___getStatistics();
    }

    /**
     * Returns the InvalidExpectations collected during the evaluation of the specs.
     *
     * @return array The InvalidExpectations collected during the evaluation of the specs.
     */
    public function getInvalidExpectations(): array
    {
        return $this->specsEvaluator->___getInvalidExpectations();
    }

    /**
     * Sets a callable callback to evaluate after the execution of each Spec run.
     * This callable has the following signature:
     *      function ($spec, $status) {}
     * and the status can be any of ['passed', 'failed', 'error', 'skipped'].
     *
     * @param callable $callable The callable to call after the execution of each Spec.
     */
    public function setOnSpecRunCallable($callable): void
    {
        $this->specsEvaluator->___setOnSpecRunCallable($callable);
    }

    /**
     *  Private - For testing purposes only.
     *
     * @return SpecEvaluator
     */
    public function ___getSpecsEvaluator(): SpecEvaluator
    {
        return $this->specsEvaluator;
    }

    /**
     * Collects and runs all the Specs from all the files located in the given directory of file.
     * It also accepts an optional SpecContextDefinitions with the global context definition to all specs run.
     *
     * @param string $directoryOrFile Can be a:
     *      - path to a directory                   tests/specs/
     *      - path to a file                        tests/specs/SomeFeatureSpec.php
     * @param int|null $lineNumber Optional - An optional line number in the case the $directoryOrFile is a file path.
     * @param SpecContextDefinitions|null $specsContextDefinitions Optional - The initial context definition for all the specs.
     */
    public function runOn(
        string $directoryOrFile, int $lineNumber = null, SpecContextDefinitions $specsContextDefinitions = null
    ): void
    {
        if (is_file($directoryOrFile)) {
            $this->runSpecFile($directoryOrFile, $lineNumber, $specsContextDefinitions);
            return;
        }

        $specFiles = $this->collectSpecFilesIn($directoryOrFile);

        $specs = $this->collectSpecsFromFiles($specFiles);

        $this->evaluateSpecs($specs, $specsContextDefinitions);
    }

    /**
     * Collects and runs all the Spec from the given single file.
     * It also accepts an optional SpecContextDefinitions with the global context definition to all specs run.
     *
     * @param string $file  Path to a file with specs.
     * @param int|null $lineNumber Optional - An optional line number in the case the $directoryOrFile is a file path.
     * @param SpecContextDefinitions|null $specsContextDefinitions Optional - The initial context definition for all the specs.
     */
    public function runSpecFile(
        string $file, int $lineNumber = null, SpecContextDefinitions $specsContextDefinitions = null
    ): void
    {
        $specs = $this->getSpecFromFile($file);

        if ($lineNumber !== null) {
            $specs->restrictToLineNumber($lineNumber);
        }

        $this->evaluateSpecs([$specs], $specsContextDefinitions);
    }

    /**
     * Collects a SpecDescription from the given file $specFile. The returned SpecDescription contains other
     * nested Specs and SpecDescriptions.
     *
     * The $specFile is expected to define the SpecDescription using a local variable named $spec
     * Example:
     *      <?php
     *
     *      $spec->describe( 'When some condition ... ', function() {
     *          $this->let( 'value', function() {
     *              return 1;
     *          });
     *
     *          $this->it( ' returns a value ... ', function() {
     *              $this->expect( $this->value ) ->to() ->equal( 1 );
     *          });
     *      });
     *
     * @param string $specFile The file to look for a SpecDescription.
     * @return SpecDescription Returns the defined SpecDescription. This SpecDescription contains nested Specs
     *  and SpecDescriptions.
     */
    public function getSpecFromFile(string $specFile): SpecDescription
    {
        $specDescription = new SpecDescription("", null, null);

        $specDescription->defineInFile($specFile);

        return $specDescription;
    }

    /**
     * Evaluates each Spec in the given $specsCollection.
     * It also accepts an optional SpecContextDefinitions with the global context definition to all specs run.
     * During the evaluation it collects statistics about the specs results.
     *
     * @param array $specsCollection An array of SpecDescriptions to evaluate.
     * @param SpecContextDefinitions|null $specsContextDefinitions Optional - The initial context definition for all
     *  the specs.
     */
    public function evaluateSpecs(array $specsCollection, SpecContextDefinitions $specsContextDefinitions = null)
    {
        $this->specsEvaluator->___reset();

        if ($specsContextDefinitions !== null) {
            $this->specsEvaluator->___configureFrom($specsContextDefinitions);
        }

        $this->specsEvaluator->___runAll($specsCollection);
    }

    /**
     * Collects all the SpecDescription defined in all the files in the given directory and its subdirectories.
     *
     * @param string $directory
     * @return array Array with the collected SpecDescriptions from all the files.
     */
    public function collectSpecFilesIn(string $directory): array
    {
        $files = [];

        $folderContents = array_diff(scandir($directory), [".", ".."]);

        foreach ($folderContents as $eachFile) {
            $specFullPath = $directory . "/" . $eachFile;

            if (is_dir($specFullPath)) {
                $files = array_merge($files, $this->collectSpecFilesIn($specFullPath));
            } else {
                $files[] = $specFullPath;
            }
        }

        return $files;
    }

    /**
     * Collects all the SpecDescription defined in the given files.
     *
     * @param array $specFiles An array of files to collect SpecDescription from.
     * @return array Array with the collected SpecDescriptions from all the files.
     */
    public function collectSpecsFromFiles(array $specFiles): array
    {
        return array_map(
            function ($eachFile){ return $this->getSpecFromFile($eachFile); },
            $specFiles
        );
    }
}