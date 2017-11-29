<?php

namespace AppBundle\File;

use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class Uploader
{
    private $pathToUploadFolder;
    private $absolutePathWebFolder;
    private $logger;

    public function __construct(LoggerInterface $logger, $pathToUploadFolder, $absolutePathWebFolder)
    {
        $this->logger = $logger;
        $this->pathToUploadFolder = $pathToUploadFolder;
        $this->absolutePathWebFolder = $absolutePathWebFolder;
    }

    /**
     * This method receives an UploadedFile object, move the file to the right directory and returns the path to this
     * file moved.
     * @param UploadedFile $file
     * @return string Path
     */
    public function upload(UploadedFile $file)
    {
        $filename = uniqid() . '-' . $file->getClientOriginalName();
        $path = $this->absolutePathWebFolder . $this->pathToUploadFolder;
        $file->move($path, $filename);

        $this->logger->notice(sprintf('The file %s has been moved to the folder %s.', $filename, $path));

        return $this->pathToUploadFolder . DIRECTORY_SEPARATOR . $filename;
    }
}