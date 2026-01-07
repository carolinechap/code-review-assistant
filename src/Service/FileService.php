<?php

namespace App\Service;

use App\Dto\CodeAnalysisResponse;
use Symfony\Component\Filesystem\Filesystem;

class FileService
{

    private Filesystem $fileSystem;

    public function __construct(private readonly string $outputDirectory = 'var/generated')
    {
        $this->fileSystem = new Filesystem();
        $this->fileSystem->mkdir($this->outputDirectory);
    }

    public function generateFile(CodeAnalysisResponse $analysis): string
    {
        $newFilename = $this->outputDirectory . '/' . time() . '.php';

        $this->fileSystem->dumpFile(
            $newFilename, $analysis->improvedCode->code
        );

        return $newFilename;

    }

}
