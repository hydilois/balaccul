<?php

namespace AccountBundle\Controller;

use AccountBundle\Entity\ReportItem;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Reportitem controller.
 *
 * @Route("reportitem")
 */
class ReportItemController extends Controller
{
    /**
     * Lists all reportItem entities.
     *
     * @Route("/", name="reportitem_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $reportItems = $em->getRepository('AccountBundle:ReportItem')->findAll();

        return $this->render('reportitem/index.html.twig', array(
            'reportItems' => $reportItems,
        ));
    }

    /**
     * Creates a new reportItem entity.
     *
     * @Route("/new/complete", name="reportitem_new_complete")
     * @Method({"GET", "POST"})
     */
    public function reportAction(Request $request){

        $em = $this->getDoctrine()->getManager();
        $agency = $em->getRepository('ConfigBundle:Agency')->find(1);

        if ($request->getMethod() == 'POST') {
            // Get the current user connected
            $currentUserId  = $this->get('security.token_storage')->getToken()->getUser()->getId();
            $currentUser    = $em->getRepository('UserBundle:Utilisateur')->find($currentUserId);

            $dateDebut = $request->get('start');
            $dateFin = $request->get('end');

            $newDateStart = explode( "/" , substr($dateDebut,strrpos($dateDebut," ")));
            $newDateEnd = explode( "/" , substr($dateFin,strrpos($dateFin," ")));

            $dateStart1  = new \DateTime($newDateStart[2]."-".$newDateStart[0]."-".$newDateStart[1]);
            $dateEnd1  = new \DateTime($newDateEnd[2]."-".$newDateEnd[0]."-".$newDateEnd[1]);

            $displayDateStart  = $newDateStart[1]."-".$newDateStart[0]."-".$newDateStart[2];
            $displayDateEnd  = $newDateEnd[1]."-".$newDateEnd[0]."-".$newDateEnd[2];

            $dateEnd11 = $dateEnd1->add(new \DateInterval('P1D'));



            $qbuilder = $em->createQueryBuilder();
            $qbuilder->select('ri')
                ->from('AccountBundle:ReportItem', 'ri')
                ->where('ri.parentItem IS NOT NULL');

            $reportItems = $qbuilder->getQuery()->getResult();
            foreach ($reportItems as $reportItem) {
                $valeur = $request->get($reportItem->getId());
                $item  = $em->getRepository('AccountBundle:ReportItem')->find($reportItem->getId());
                $item->setAmount($valeur);
                $em->persist($item);
                
            }
            $em->flush();

            $qbuilder->select('ti')
                ->from('ConfigBundle:TransactionIncome', 'ti')
                ->where('ti.transactionDate BETWEEN :date1 AND :date2')
                ->setParameters(
                    [
                    'date1' => $dateStart1->format('Y-m-d'),
                    'date2' => $dateEnd1->format('Y-m-d'),
                    ]
                );
            $transactionIncomes = $qbuilder->getQuery()->getResult();
            
            $qbuilder1 = $em->createQueryBuilder();
            $qbuilder1->select('ri')
                ->from('AccountBundle:ReportItem', 'ri')
                ->where('ri.parentItem IS NOT NULL');

            $reportItemsFinal = $qbuilder1->getQuery()->getResult();

            $totalIncome = 0;
            foreach ($transactionIncomes as $item) {
                $totalIncome += $item->getAmount();
            }

            $totalIncomeMonth = 0;
            foreach ($reportItemsFinal as $value) {
                $totalIncomeMonth += $value->getAmount();
            }
            if ($totalIncomeMonth < $totalIncome) {
                // die("fdsfdsfdsf  ".$totalIncome);
                $this->addFlash('warning', 'The income of the month is not correct');
                return $this->render('report/report_month.html.twig', [
                    'items' => $reportItems,
                ]);
            }

            $date = new \DateTime('now');

            $html =  $this->renderView('reportitem/monthly_report_file.html.twig', array(
                'totalIncome' => $totalIncome,
                'agency' => $agency,
                'date' => $date,
                'items' => $reportItemsFinal,
                'currentUser' => $currentUser,
                'displayDateStart' => $displayDateStart,
                'displayDateEnd' => $displayDateEnd,
            ));

            $html2pdf = $this->get('html2pdf_factory')->create('P', 'A4', 'en', true, 'UTF-8', array(5, 10, 5, 10));
            $html2pdf->pdf->SetAuthor('GreenSoft-Team');
            $html2pdf->pdf->SetDisplayMode('real');
            $html2pdf->pdf->SetTitle('Monthly_report_'.$displayDateStart."_".$displayDateEnd);
            $response = new Response();
            $html2pdf->pdf->SetTitle('Monthly_report_'.$displayDateStart."_".$displayDateEnd);
            $html2pdf->writeHTML($html);
            $content = $html2pdf->Output('', true);
            $response->setContent($content);
            $response->headers->set('Content-Type', 'application/pdf');
            $response->headers->set('Content-disposition', 'filename=Monthly_report_'.$displayDateStart.'_'.$displayDateEnd.'.pdf');
            return $response;
        }
    }



     /**
     * Creates a new reportItem entity.
     *
     * @Route("/new", name="reportitem_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        $reportItem = new Reportitem();
        $form = $this->createForm('AccountBundle\Form\ReportItemType', $reportItem);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($reportItem);
            $em->flush();

            return $this->redirectToRoute('reportitem_index');
        }

        return $this->render('reportitem/new.html.twig', array(
            'reportItem' => $reportItem,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a reportItem entity.
     *
     * @Route("/{id}", name="reportitem_show")
     * @Method("GET")
     */
    public function showAction(ReportItem $reportItem)
    {
        $deleteForm = $this->createDeleteForm($reportItem);

        return $this->render('reportitem/show.html.twig', array(
            'reportItem' => $reportItem,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing reportItem entity.
     *
     * @Route("/{id}/edit", name="reportitem_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, ReportItem $reportItem)
    {
        $deleteForm = $this->createDeleteForm($reportItem);
        $editForm = $this->createForm('AccountBundle\Form\ReportItemType', $reportItem);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('reportitem_edit', array('id' => $reportItem->getId()));
        }

        return $this->render('reportitem/edit.html.twig', array(
            'reportItem' => $reportItem,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a reportItem entity.
     *
     * @Route("/{id}", name="reportitem_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, ReportItem $reportItem)
    {
        $form = $this->createDeleteForm($reportItem);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($reportItem);
            $em->flush();
        }

        return $this->redirectToRoute('reportitem_index');
    }

    /**
     * Creates a form to delete a reportItem entity.
     *
     * @param ReportItem $reportItem The reportItem entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(ReportItem $reportItem)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('reportitem_delete', array('id' => $reportItem->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}
