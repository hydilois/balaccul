<?php

// src/Service/DatabaseBackupManager.php
namespace App\Service;

use App\Entity\Backup;

/**
 * Class DbBackupManager
 * @package App\Service
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
     * @return bool
     */
    public function backup(string $user = null, string $password = null, string $databaseName): bool
    {
        $date = date("Y-m-d_H:i:s");
        $filName = 'School-Lab_DB_'.$date;
        exec('mysqldump --skip-add-locks -u '.$user.' -p'.$password.' --databases '.$databaseName.' > '.$this->getTargetDir().'/School-Lab_DB'.$date.'.sql');

        $backup = new Backup();
        $backup->setFileName($filName);
        $backup->setPath('databases/School-Lab_DB'.$date.'.sql');
        $this->getEntityManager()->persist($backup);
        $this->getEntityManager()->flush();
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