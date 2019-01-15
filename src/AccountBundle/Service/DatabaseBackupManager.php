<?php

namespace AccountBundle\Service;

use ConfigBundle\Entity\Backup;

/**
 * Class DbBackupManager
 * @package AccountBundle\Service
 */
class DatabaseBackupManager
{
    /**
     * @var
     */
    private $targetDir;

    /**
     * @var
     */
    private $entityManager;

    /**
     * DatabaseBackupManager constructor.
     * @param $targetDir
     * @param $entityManager
     */
    public function __construct($targetDir, $entityManager)
    {
        $this->targetDir = $targetDir;
        $this->entityManager = $entityManager;
    }

    /**
     * @param string $user
     * @param string $password
     * @param string $databaseName
     * @param string $operation
     * @param FileUploader $fileUploader
     * @return bool
     */
    public function backup($user = null, $password = null, $databaseName, FileUploader $fileUploader, $operation='Manual Backup')
    {
        $date = date("Y-m-d_H:i:s");
        $filName = 'HYMIF_DB_'.$date;

        $backup = new Backup();
        $backup->setFileName($filName);
        $backup->setPath('archives/databases/HYMIF_DB_'.$date.'.sql');
        $backup->setOperation($operation);
        $this->getEntityManager()->persist($backup);
        $this->getEntityManager()->flush();
        exec('mysqldump --skip-add-locks -u '.$user.' -p'.$password.' --databases '.$databaseName.' > '.$this->getTargetDir().'/HYMIF_DB_'.$date.'.sql');

        $databases = $this->getEntityManager()->getRepository(Backup::class)->findAll();
        if (count($databases) > 20) {
            $databaseTmp = $this->getEntityManager()->getRepository(Backup::class)->findOneBy([], ['id' => 'ASC']);
            $fileUploader->removeFile($databaseTmp->getPath());
            $this->getEntityManager()->remove($databaseTmp);
            $this->getEntityManager()->flush();
        }
        return true;
    }

    /**
     * @return mixed
     */
    public function getTargetDir()
    {
        return $this->targetDir;
    }

    /**
     * @return mixed
     */
    public function getEntityManager()
    {
        return $this->entityManager;
    }
}