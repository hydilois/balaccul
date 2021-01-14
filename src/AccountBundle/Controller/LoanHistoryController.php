<?php

namespace AccountBundle\Controller;

use AccountBundle\Entity\Loan;
use AccountBundle\Entity\LoanHistory;
use AccountBundle\Form\LoanHistoryType;
use ConfigBundle\Entity\TransactionIncome;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Loanhistory controller.
 *
 * @Route("loanhistory")
 */
class LoanHistoryController extends Controller
{
    /**
     * Lists all loanHistory entities.
     *
     * @Route("/", name="loanhistory_index")
     * @Method("GET")
     */
    public function indexAction(){

        $em = $this->getDoctrine()->getManager();

        $loanHistories = $em->getRepository('AccountBundle:LoanHistory')->findAll();

        return $this->render('loanhistory/index.html.twig', array(
            'loanHistories' => $loanHistories,
        ));
    }


    /**
     * Finds and displays all loans situation.
     *
     * @Route("/loans/situation", name="all_loans_situation")
     * @Method("GET")
     */
    public function allLoansSituationAction()
    {
        $em = $this->getDoctrine()->getManager();
        $agency = $em->getRepository('ConfigBundle:Agency')->findOneBy([],['id' => 'ASC']);
        $members = $em->getRepository('MemberBundle:Member')->findBy([], ['memberNumber' => 'ASC']);
        $loans = [];
        foreach ($members as $member) {
            $tmpLoan = $em->getRepository(Loan::class)->getMemberLoans($member, New \DateTime('now'));

            if ($tmpLoan) {
                $loan = $em->getRepository(LoanHistory::class)->getActiveLoanPerMember($tmpLoan, new \DateTime('now'));
                array_push($loans, $loan);
            }
        }

        $template =  $this->renderView('loanhistory/members_loans_situation_pdf.html.twig', [
            'agency' => $agency,
            'loans' => $loans,
        ]);

        $title = 'Members_Loans_Situation';
        $html2PdfService = $this->get('app.html2pdf');
        $html2PdfService->create('L', 'A4', 'en', true, 'UTF-8', array(10, 5, 10, 10));
        return $html2PdfService->generatePdf($template, $title.'.pdf', 'loans',$title, 'FI');
    }


    /**
     * Lists all loanHistory entities.
     *
     * @Route("/new/payment", name="loanhistory_new_payement")
     * @Method("GET")
     */
    public function loanPaymentAction(){
        $em = $this->getDoctrine()->getManager();

        $loans = $em->getRepository('AccountBundle:Loan')->findBy(['status' => true]);

        $loanHistory = new Loanhistory();
        $form = $this->createForm(LoanHistoryType::class, $loanHistory);

        return $this->render('loanhistory/loan_payement.html.twig', array(
            'loans' => $loans,
            'form' => $form->createView(),
        ));
    }

    /**
     * Creates a new loanHistory entity.
     *
     * @Route("/new", name="loanhistory_new")
     * @Method({"GET", "POST"})
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
     */
    public function newAction(Request $request)
    {
        $loanHistory = new Loanhistory();
        $form = $this->createForm('AccountBundle\Form\LoanHistoryType', $loanHistory);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($loanHistory);
            $em->flush();

            return $this->redirectToRoute('loanhistory_show', array('id' => $loanHistory->getId()));
        }

        return $this->render('loanhistory/new.html.twig', array(
            'loanHistory' => $loanHistory,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a loanHistory entity.
     *
     * @Route("/{id}", name="loanhistory_show")
     * @Method("GET")
     * @param LoanHistory $loanHistory
     * @return Response
     */
    public function showAction(LoanHistory $loanHistory)
    {
        $deleteForm = $this->createDeleteForm($loanHistory);

        return $this->render('loanhistory/show.html.twig', array(
            'loanHistory' => $loanHistory,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing loanHistory entity.
     *
     * @Route("/{id}/edit", name="loanhistory_edit")
     * @Method({"GET", "POST"})
     * @param Request $request
     * @param LoanHistory $loanHistory
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
     */
    public function editAction(Request $request, LoanHistory $loanHistory)
    {
        $deleteForm = $this->createDeleteForm($loanHistory);
        $editForm = $this->createForm('AccountBundle\Form\LoanHistoryType', $loanHistory);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('loanhistory_edit', array('id' => $loanHistory->getId()));
        }

        return $this->render('loanhistory/edit.html.twig', array(
            'loanHistory' => $loanHistory,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a loanHistory entity.
     *
     * @Route("/{id}", name="loanhistory_delete")
     * @Method("DELETE")
     * @param Request $request
     * @param LoanHistory $loanHistory
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function deleteAction(Request $request, LoanHistory $loanHistory)
    {
        $form = $this->createDeleteForm($loanHistory);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($loanHistory);
            $em->flush();
        }

        return $this->redirectToRoute('loanhistory_index');
    }

    /**
     * Creates a form to delete a loanHistory entity.
     *
     * @param LoanHistory $loanHistory The loanHistory entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(LoanHistory $loanHistory)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('loanhistory_delete', array('id' => $loanHistory->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }


    /**
     * @param Request $request [contains the http request that is passed on]
     * 
     * @Route("/save", name="loanhistory_new_save")
     * @Method({"GET", "POST"})
     * @return Response
     */
    function saveLoanHistoryFromJSON(Request $request)
    {
        $entityManager = $this->getDoctrine()->getManager();

        // Get the current user connected
        $currentUserId  = $this->get('security.token_storage')->getToken()->getUser()->getId();
        $currentUser    = $entityManager->getRepository('UserBundle:Utilisateur')->find($currentUserId);


        try{
            $loanHistory = new Loanhistory();
            //first thing we get the classe with the JSON format
            $loanHistoryJSON = json_decode(json_encode($request->request->get('data')), true);

            $loanHistory->setCurrentUser($currentUser);
            $loanHistory->setCloseLoan(false);
            $loanHistory->setMonthlyPayement($loanHistoryJSON["monthlyPayment"]);
            $loanHistory->setInterest($loanHistoryJSON["interest"]);

            $loan    = $entityManager->getRepository('AccountBundle:Loan')->find($loanHistoryJSON["loanCode"]);
            
            $lowest_remain_amount_LoanHistory = $entityManager->createQueryBuilder()
                ->select('MIN(lh.remainAmount)')
                ->from('AccountBundle:LoanHistory', 'lh')
                ->innerJoin('AccountBundle:Loan', 'l', 'WITH','lh.loan = l.id')
                ->where('l.id = :loan')->setParameter('loan', $loan)
                ->getQuery()
                ->getSingleScalarResult();

            $latestLoanHistory = $entityManager->getRepository('AccountBundle:LoanHistory')->findOneBy(
                [
                    'remainAmount' => $lowest_remain_amount_LoanHistory,
                    'loan' => $loan
                ],
                [
                    'id' => 'DESC'
                ]
            );


            if ($latestLoanHistory) {
                //set the unpaid to recover after in the next payment
                $loanHistory->setRemainAmount($latestLoanHistory->getRemainAmount() - $loanHistoryJSON["monthlyPayment"]);

                $interest = ($loanHistory->getRemainAmount() * $loan->getRate())/100;
                $dailyInterestPayment = $interest/30;
                
                $date = strtotime($latestLoanHistory->getDateOperation()->format('Y-m-d'));
                $dateNow = time();

                $interestToPay = $dailyInterestPayment * floor(($dateNow - $date)/(60*60*24));

                if($interestToPay + $latestLoanHistory->getUnpaidInterest() - $loanHistoryJSON["interest"] < 0){
                    $loanHistory->setUnpaidInterest(0);
                }else{

                    $loanHistory->setUnpaidInterest($interestToPay + $latestLoanHistory->getUnpaidInterest() - $loanHistoryJSON["interest"]);
                }

            }else{

                $interest = ($loan->getLoanAmount() * $loan->getRate())/100;
                $dailyInterestPayment = $interest/30;
                
                $date = strtotime($loan->getDateLoan()->format('Y-m-d'));
                $dateNow = time();

                $interestToPay = $dailyInterestPayment * floor(($dateNow - $date)/(60*60*24));
                if ($interestToPay- $loanHistoryJSON["interest"] < 0 ) {
                    $loanHistory->setUnpaidInterest(0);
                }else{
                    $loanHistory->setUnpaidInterest($interestToPay- $loanHistoryJSON["interest"]);
                }


                $loanHistory->setRemainAmount($loan->getLoanAmount() - $loanHistoryJSON["monthlyPayment"]);
            }
            
            
            $loanHistory->setLoan($loan);

            $income  = new TransactionIncome();

            $income->setAmount($loanHistoryJSON["interest"]);
            $income->setDescription("Loan Interest payment. Loan Code: ".$loan->getLoanCode()." // Amount: ".$loanHistoryJSON["interest"]);


            //update the cash in hand
            $cashInHandAccount  = $entityManager->getRepository('ClassBundle:InternalAccount')->find(9);
            $cashInHandAccount->setAmount($cashInHandAccount->getAmount() + $loanHistoryJSON["interest"] + $loanHistoryJSON["monthlyPayment"]);

            $entityManager->persist($cashInHandAccount);
            
            /**
            *** Making record here
            **/
            
            $entityManager->persist($loanHistory);
            $entityManager->persist($income);
            $entityManager->flush();

            $response["data"]               = $loanHistoryJSON;
            $response["optionalData"]       = json_encode($loanHistory->getId());
            $response["success"] = true;

            return new Response(json_encode($response));
       
        }catch(Exception $ex){

            $response["success"] = false;
            return new Response(json_encode($response));
        }
    }


    /**
     * @param Request $request [contains the http request that is passed on]
     * 
     * @Route("/close", name="loan_save_close")
     * @Method({"GET", "POST"})
     * @return Response
     */
    function closeLoanFromJSON(Request $request){
        $entityManager = $this->getDoctrine()->getManager();
        try{
            $loanJSON = json_decode(json_encode($request->request->get('data')), true);

            if ($loanJSON["unpaidInterest"] > 0 || $loanJSON["loanRemaint"] > 0 ) {
                        return json_encode([
                            "message" => "Impossible to close the loan Check the loan unpaid or the unpaid Interest", 
                            "params" => $loanJSON, 
                            "status" => "failed"
                        ]);
            }
            $loan = $entityManager->getRepository("AccountBundle:Loan")->find($loanJSON["loanId"]);
            $loan->setStatus(false);
            $loan->setLoanCode(uniqid());
            $entityManager->flush();

            return json_encode([
                "message" => "The loan has been closed", 
                "params" => $loanJSON,
                "status" => "success"
            ]);
       
        }catch(Exception $ex){

            $response["success"] = false;
            return new Response(json_encode($response));
        }
    }
}
