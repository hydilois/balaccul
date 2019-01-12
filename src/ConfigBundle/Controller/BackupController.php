<?php

namespace ConfigBundle\Controller;

use AccountBundle\Service\FileUploader;
use ConfigBundle\Entity\Backup;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Student controller.
 *
 * @Route("backup")
 */
class BackupController extends Controller
{
    /**
     * @Route("/", name="backup_index")
     * @return Response
     * @Security("is_granted('IS_AUTHENTICATED_FULLY')")
     */
    public function index()
    {
        $databases = $this->getDoctrine()->getManager()->getRepository(Backup::class)->findBy([],['createdAt' => 'ASC']);
        return $this->render('backup/index.html.twig', [
            'databases' => $databases
        ]);
    }

    /**
     * Remove the specified resource from storage.
     * @param Request $request
     * @param  Backup $backup
     *
     * @return Response
     * @Route("/{id}/delete", name="database_delete")
     * @Security("is_granted('IS_AUTHENTICATED_FULLY')")
     */
    public function delete( Request $request, Backup $backup)
    {
        if ($request->isXmlHttpRequest()) {
            try {
                $entityManager = $this->getDoctrine()->getManager();
                $entityManager->remove($backup);
                $entityManager->flush();

                return new Response(json_encode([
                    'message' => "Entity deleted",
                    'status' => 'success',
                ]));
            } catch (\Exception $e) {
                return new Response(json_encode([
                    'message' => $e->getMessage(),
                    'status' => 'failed',
                ]));
            }
        }
    }

    /**
     *
     * @param Backup $backup
     * @return Response
     * @Route("/{id}/restore", name="database_restore")
     * @Security("is_granted('IS_AUTHENTICATED_FULLY')")
     */
    public function restoreDatabase(Backup $backup)
    {
        $date = date("Y-m-d_H:i:s");
        $db_user = $this->getParameter('database_user');
        $db_pass = $this->getParameter('database_password');
        $db_name = $this->getParameter('database_name');

       exec(
            'echo "SET FOREIGN_KEY_CHECKS = 0;" > archives/databases/temp_'.$date.'.txt; \
                mysqldump -u'.$db_user.' -p'.$db_pass.' --add-drop-table --no-data '.$db_name.' | grep ^DROP >> archives/databases/temp_'.$date.'.txt; \
                echo "SET FOREIGN_KEY_CHECKS = 1;" >> archives/databases/temp_'.$date.'.txt; \
                mysql -u'.$db_user.' -p'.$db_pass.' '.$db_name.' < archives/databases/temp_'.$date.'.txt;'
        );

        exec('mysql -u'.$db_user.' -p'.$db_pass.'  '.$db_name.' < '.$backup->getPath());

        return $this->redirectToRoute('fos_user_security_login');
    }
}
