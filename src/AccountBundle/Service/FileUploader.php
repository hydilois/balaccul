<?php

// AccountBundle/Service/FileUploader.php

namespace AccountBundle\Service;

use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\File\UploadedFile;

/**
 * Class FileUploader
 * @package AccountBundle\Service
 */
class FileUploader
{
    /**
     * @var
     */
    private $targetDir;

    /**
     * FileUploader constructor.
     * @param $targetDir
     */
    public function __construct($targetDir)
    {
        $this->targetDir = $targetDir;
    }

    /**
     * @param UploadedFile $file
     * @param string $directoryName
     * @return string
     */
    public function upload(UploadedFile $file, $directoryName = null)
    {
        $fileName = 'uploads/'.$directoryName.'/'.md5(uniqid()).'.'.$file->guessExtension();

        $file->move($this->getTargetDir().'/'.$directoryName, $fileName);

        return $fileName;
    }

    /**
    * @param string $fileName
    * @return void
    */
    public function removeFile($fileName)
    {
        $filesystem = new Filesystem();
        $filesystem->remove($fileName);
    }

    /**
     * @return mixed
     */
    public function getTargetDir()
    {
        return $this->targetDir;
    }
}