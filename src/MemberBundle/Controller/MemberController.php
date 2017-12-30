<?php

namespace MemberBundle\Controller;

use MemberBundle\Entity\Member;
use AccountBundle\Entity\Operation;
use ConfigBundle\Entity\TransactionIncome;
use MemberBundle\Entity\Beneficiary;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use ReportBundle\Entity\GeneralLedgerBalance;

/**
 * Member controller.
 *
 * @Route("member")
 */
class MemberController extends Controller
{
    /**
     * Lists all member entities.
     *
     * @Route("/", name="member_index")
     * @Method("GET")
     */
    public function indexAction(){
        
        $entityManager = $this->getDoctrine()->getManager();

        $members = $entityManager->getRepository('MemberBundle:Member')->findAll();

        return $this->render('member/index.html.twig', array(
            'members' => $members,
        ));
    }

    /**
     * Creates a new member entity.
     *
     * @Route("/new", name="member_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request){
        $member = new Member();
        $beneficiary = new Beneficiary();
        $form = $this->createForm('MemberBundle\Form\MemberType', $member);
        $bForm = $this->createForm('MemberBundle\Form\BeneficiaryType', $beneficiary);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($member);
            $em->flush();

            return $this->redirectToRoute('member_index');
        }

        return $this->render('member/new.html.twig', array(
            'member' => $member,
            'bForm' => $bForm->createView(),
            'form' => $form->createView(),
        ));
    }


    /**
     * Creates a new beneficiary entity.
     *
     * @Route("/beneficiary/{id}/new", name="beneficiary_new")
     * @Method({"GET", "POST"})
     */
    public function newBeneficiaryAction(Request $request, Member $member){
        $beneficiary = new Beneficiary();
        $bForm = $this->createForm('MemberBundle\Form\BeneficiaryType', $beneficiary);
        $beneficiary->setIdMember($member);
        $bForm->handleRequest($request);

        if ($request->getMethod() == "POST") {
            $em = $this->getDoctrine()->getManager();
            $totalRatio = $em->createQueryBuilder()
            ->select('SUM(b.ratio)')
            ->from('MemberBundle:Beneficiary', 'b')
            ->innerJoin('MemberBundle:Member', 'm', 'WITH','m.id = b.idMember')
            ->where('m.id = :idMember')
            ->setParameter('idMember',  $member->getId())
            ->getQuery()
            ->getSingleScalarResult();

            if (((int)$totalRatio + $beneficiary->getRatio()) > 100) {
                $this->addFlash('warning', 'Check the Ratio Value');
                return $this->redirectToRoute('member_show', array('id' => $member->getId()));

                // die("Mauvais");
            }else{
                $em->persist($beneficiary);
                $em->flush();
            }
            return $this->redirectToRoute('member_index');
        }

        return $this->render('member/new_beneficiary.html.twig', array(
            'bForm' => $bForm->createView(),
            'member' => $member,
            'beneficiary' => $beneficiary,
        ));
    }

    /**
     * Finds and displays a member entity.
     *
     * @Route("/{id}/receipt", name="member_registration_receipt")
     * @Method("GET")
     */
    public function memberRegistrationReceiptAction(Member $member){

        $em = $this->getDoctrine()->getManager();
        $agency = $em->getRepository('ConfigBundle:Agency')->find(1);

        $memberName = str_replace(' ', '_', $member->getName());


        $html =  $this->renderView('member/registration_fees_receipt_file.html.twig', array(
            'agency' => $agency,
            'member' => $member,
        ));

        $html2pdf = $this->get('html2pdf_factory')->create('P', 'A4', 'en', true, 'UTF-8', array(10, 5, 10, 10));
        $html2pdf->pdf->SetAuthor('GreenSoft-Team');
        $html2pdf->pdf->SetDisplayMode('real');
        $html2pdf->pdf->SetTitle('Receipt_registration_'.$memberName);
        $response = new Response();
        $html2pdf->pdf->SetTitle('Registration_Receipt_'.$memberName);
        $html2pdf->writeHTML($html);
        $content = $html2pdf->Output('', true);
        $response->setContent($content);
        $response->headers->set('Content-Type', 'application/pdf');
        $response->headers->set('Content-disposition', 'filename=Registration_Receipt_'.$memberName.'.pdf');
        return $response;
    }
    
    /**
     * Finds and displays a member entity.
     *
     * @Route("/{id}/show", name="member_show")
     * @Method("GET")
     */
    public function showAction(Member $member){

        $deleteForm = $this->createDeleteForm($member);
        
        $em = $this->getDoctrine()->getManager();
         $beneficiaries = $em->getRepository('MemberBundle:Beneficiary')->findByIdMember($member);
        return $this->render('member/show.html.twig', array(
            'member' => $member,
            'beneficiaries' => $beneficiaries,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing member entity.
     *
     * @Route("/{id}/edit", name="member_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, Member $member){
        $deleteForm = $this->createDeleteForm($member);
        $editForm = $this->createForm('MemberBundle\Form\MemberEditType', $member);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();
            return $this->redirectToRoute('member_index');
        }

        return $this->render('member/edit.html.twig', array(
            'member' => $member,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a member entity.
     *
     * @Route("/{id}", name="member_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, Member $member){
        $form = $this->createDeleteForm($member);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($member);
            $em->flush();
        }

        return $this->redirectToRoute('member_index');
    }

    /**
     * Creates a form to delete a member entity.
     *
     * @param Member $member The member entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Member $member)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('member_delete', array('id' => $member->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }

    /**
     * @param Request $request [contains the http request that is passed on]
     * 
     * @Route("/new_json", name="member_new_json")
     * @Method({"GET", "POST"})
     */
    function addNewMemberFromJSON(Request $request){

        $entityManager = $this->getDoctrine()->getManager();
        $logger = $this->get('logger');
        $member = new Member();
            //first thing we get the member with the JSON format
            $memberJSON = json_decode(json_encode($request->request->get('data')), true);
            try{
                $totalPercent = 0;
                foreach ($memberJSON["beneficiary"] as $key => $value) {
                    if ($value['name'] != "") {
                        $totalPercent += $value['ratio'];
                    }
                }
                if ($totalPercent > 100) {
                            return json_encode([
                                "message" => "The Sum of Beneficiary Ratio should not be more than 100 %", 
                                "params" => $memberJSON, 
                                "status" => "failed"
                            ]);
                }
            }catch(Exception $ex){
                $logger->error('SOMETHING WENT WRONG : MemberController : trying to insert Beneficiary');
            }

        try{
            $member->setName($memberJSON["name"]);
            $member->setSex($memberJSON["sex"]);
            $member->setDateOfBirth(new \DateTime($memberJSON["dateOfBirth"]));
            $member->setPlaceOfBirth($memberJSON["placeOfBirth"]);
            $member->setOccupation($memberJSON["occupation"]);
            $member->setAddress($memberJSON["address"]);
            $member->setNicNumber($memberJSON["nicNumber"]);
            $member->setIssuedOn(new \DateTime($memberJSON["issuedOn"]));
            $member->setIssuedAt($memberJSON["issuedAt"]);
            $member->setProposedBy($memberJSON["proposedBy"]);
            $member->setIsAproved($memberJSON["isAproved"]);
            $member->setAprovedBy($memberJSON["aprovedBy"]);
            $member->setMemberNumber($memberJSON["memberNumber"]);
            $member->setDoneAt($memberJSON["doneAt"]);
            $member->setMembershipDateCreation(new \DateTime($memberJSON["membershipDateCreation"]));
            $member->setWitnessName($memberJSON["witnessName"]);
            $member->setPhoneNumber($memberJSON["phoneNumber"]);
            $member->setRegistrationFees($memberJSON["registrationFees"]);
            $member->setShare($memberJSON["share"]);
            $member->setSaving($memberJSON["saving"]);
            $member->setDeposit($memberJSON["deposit"]);
            $member->setBuildingFees($memberJSON["buildingFees"]);

            /**
             * making recordds here
             * --------------------
             */
        
            $entityManager->persist($member);
            $entityManager->flush();

        }catch(Exception $ex){

            $logger("AN ERROR OCCURED");
            $response["success"] = false;
        }

        /**
         * At this point we need to register all the dependant objects
         * ---------------------
         */
        try{
            foreach ($memberJSON["beneficiary"] as $key => $value) {
                if ($value['name'] != "") {
                    $beneficiary = new Beneficiary();
                    $beneficiary->setIdMember($member);
                    $beneficiary->setName($value['name']);
                    $beneficiary->setRelation($value['relation']);
                    $beneficiary->setRatio($value['ratio']);

                    $entityManager->persist($beneficiary);
                    $entityManager->flush();
                }
            }
        }catch(Exception $ex){
            $logger->error('SOMETHING WENT WRONG : MemberController : trying to insert Beneficiary');
        }

        $currentUserId  = $this->get('security.token_storage')->getToken()->getUser()->getId();
        $currentUser    = $entityManager->getRepository('UserBundle:Utilisateur')->find($currentUserId);

        /**
        * Record the member situaton at the the creation
        */
        if ($member->getShare() != 0) { //memmber shares is not null
            $operationShare = new Operation();
            $operationShare->setIsShare(true);
            $operationShare->setCurrentUser($currentUser);
            $operationShare->setAmount($member->getShare());
            $operationShare->setBalance($member->getShare());
            $operationShare->setTypeOperation(Operation::TYPE_CASH_IN);
            $operationShare->setMember($member);
            $operationShare->setRepresentative($member->getName());

            $entityManager->persist($operationShare);

            $memberShares  = $entityManager->getRepository('ClassBundle:InternalAccount')->find(1);
            $memberShares->setBalance($memberShares->getBalance() + $member->getShare());

            $classe = $entityManager->getRepository('ClassBundle:Classe')->find($account->getClasse()->getId());
            $classe->setBalance($classe->getBalance() + $amount);

            $ledgerBalanceSha = new GeneralLedgerBalance();
            $ledgerBalanceSha->setDebit($member->getShare());
            $ledgerBalanceSha->setCurrentUser($currentUser);
            $latestEntryGBL = $entityManager->getRepository('ReportBundle:GeneralLedgerBalance')->findOneBy(
                [],
                ['id' => 'DESC']);
            if ($latestEntryGBL) {
                $ledgerBalanceSha->setBalance($latestEntryGBL->getBalance() + $member->getShare());
            }else{
                $ledgerBalanceSha->setBalance($member->getShare());
            }
            $ledgerBalanceSha->setTypeOperation(Operation::TYPE_CASH_IN);
            $ledgerBalanceSha->setAccount($memberShares);
            $ledgerBalanceSha->setRepresentative($member->getName());
            $ledgerBalanceSha->setAccountBalance($memberShares->getBalance());
            $ledgerBalanceSha->setAccountTitle($memberShares->getAccountName()." A/C ".$member->getMemberNumber());
            $ledgerBalanceSha->setMember($member);
                /*Make record*/ 
            $entityManager->persist($ledgerBalanceSha);
            $entityManager->flush();
        }

        if ($member->getSaving() != 0) {//Member savings is not null
            $operationSaving = new Operation();
            $operationSaving->setIsSaving(true);
            $operationSaving->setCurrentUser($currentUser);
            $operationSaving->setAmount($member->getSaving());
            $operationSaving->setBalance($member->getSaving());
            $operationSaving->setTypeOperation(Operation::TYPE_CASH_IN);
            $operationSaving->setMember($member);
            $operationSaving->setRepresentative($member->getName());

            $entityManager->persist($operationSaving);

            $memberSavings  = $entityManager->getRepository('ClassBundle:InternalAccount')->find(44);
            $memberSavings->setBalance($memberSavings->getBalance() + $member->getSaving());

            /*First step*/ 
            $ledgerBalance = new GeneralLedgerBalance();
            $ledgerBalance->setDebit($member->getSaving());
            $ledgerBalance->setCurrentUser($currentUser);
            $latestEntryGBL = $entityManager->getRepository('ReportBundle:GeneralLedgerBalance')->findOneBy(
                [],
                ['id' => 'DESC']);
            if ($latestEntryGBL) {
                $ledgerBalance->setBalance($latestEntryGBL->getBalance() + $member->getSaving());
            }else{
                $ledgerBalance->setBalance($member->getSaving());
            }
            $ledgerBalance->setTypeOperation(Operation::TYPE_CASH_IN);
            $ledgerBalance->setAccount($memberSavings);
            $ledgerBalance->setRepresentative($member->getName());
            $ledgerBalance->setAccountBalance($memberSavings->getBalance());
            $ledgerBalance->setAccountTitle($memberSavings->getAccountName()." A/C_".$member->getMemberNumber());
            $ledgerBalance->setMember($member);
            /*Make record*/ 
            $entityManager->persist($ledgerBalance);
            $entityManager->flush();

        }

        if ($member->getDeposit() != 0) {//member deposit is not null
            $operationDeposit = new Operation();
            $operationDeposit->setIsDeposit(true);
            $operationDeposit->setCurrentUser($currentUser);
            $operationDeposit->setAmount($member->getDeposit());
            $operationDeposit->setBalance($member->getDeposit());
            $operationDeposit->setTypeOperation(Operation::TYPE_CASH_IN);
            $operationDeposit->setMember($member);
            $operationDeposit->setRepresentative($member->getName());

            $entityManager->persist($operationDeposit);

            $memberDeposits  = $entityManager->getRepository('ClassBundle:InternalAccount')->find(42);
            $memberDeposits->setBalance($memberDeposits->getBalance() + $member->getDeposit());

            $ledgerBalanceDep = new GeneralLedgerBalance();
            $ledgerBalanceDep->setDebit($member->getDeposit());
            $ledgerBalanceDep->setCurrentUser($currentUser);
            $latestEntryGBL = $entityManager->getRepository('ReportBundle:GeneralLedgerBalance')->findOneBy(
                [],
                ['id' => 'DESC']);
            if ($latestEntryGBL) {
                $ledgerBalanceDep->setBalance($latestEntryGBL->getBalance() + $member->getDeposit());
            }else{
                $ledgerBalanceDep->setBalance($member->getDeposit());
            }
            $ledgerBalanceDep->setTypeOperation(Operation::TYPE_CASH_IN);
            $ledgerBalanceDep->setAccount($memberDeposits);
            $ledgerBalanceDep->setRepresentative($member->getName());
            $ledgerBalanceDep->setAccountBalance($memberDeposits->getBalance());
            $ledgerBalanceDep->setAccountTitle($memberDeposits->getAccountName()." A/C_".$member->getMemberNumber());
            $ledgerBalanceDep->setMember($member);
            /*Make record*/ 
            $entityManager->persist($ledgerBalanceDep);
            $entityManager->flush();
        }

        
        if ($member->getRegistrationFees() != 0) {//Member registration fees
            $memberEntranceFees  = $entityManager->getRepository('ClassBundle:InternalAccount')->find(151);
            $memberEntranceFees->setBalance($memberEntranceFees->getBalance() + $member->getRegistrationFees());

            $operationRegis = new Operation();
            $operationRegis->setCurrentUser($currentUser);
            $operationRegis->setTypeOperation(Operation::TYPE_CASH_IN);
            $operationRegis->setAmount($member->getRegistrationFees());
            $operationRegis->setMember($member);
            $operationRegis->setRepresentative($member->getName());
            $operationRegis->setBalance($memberEntranceFees->getBalance());
            $entityManager->persist($operationRegis);

            // first Step
            $ledgerBalanceRegistration = new GeneralLedgerBalance();
            $ledgerBalanceRegistration->setDebit($member->getRegistrationFees());
            $ledgerBalanceRegistration->setCurrentUser($currentUser);
            $latestEntryGBL = $entityManager->getRepository('ReportBundle:GeneralLedgerBalance')->findOneBy(
                [],
                ['id' => 'DESC']);
            if ($latestEntryGBL) {
                $ledgerBalanceRegistration->setBalance($latestEntryGBL->getBalance() + $member->getRegistrationFees());
            }else{
                $ledgerBalanceRegistration->setBalance($member->getRegistrationFees());
            }
            $ledgerBalanceRegistration->setTypeOperation(Operation::TYPE_CASH_IN);
            $ledgerBalanceRegistration->setAccount($memberEntranceFees);
            $ledgerBalanceRegistration->setRepresentative($member->getName());
            $ledgerBalanceRegistration->setAccountBalance($memberEntranceFees->getBalance());
            $ledgerBalanceRegistration->setAccountTitle($memberEntranceFees->getAccountName()." A/C_".$member->getMemberNumber());
            $ledgerBalanceRegistration->setMember($member);
            /*Make record*/ 
            $entityManager->persist($ledgerBalanceRegistration);
        }

        if ($member->getBuildingFees() != 0) {//Member building fees
            $memberBuildingFees  = $entityManager->getRepository('ClassBundle:InternalAccount')->find(6);
            $memberBuildingFees->setBalance($memberBuildingFees->getBalance() + $member->getBuildingFees());

            $operationFees = new Operation();
            $operationFees->setCurrentUser($currentUser);
            $operationFees->setTypeOperation(Operation::TYPE_CASH_IN);
            $operationFees->setAmount($member->getBuildingFees());
            $operationFees->setMember($member);
            $operationFees->setRepresentative($member->getName());
            $operationFees->setBalance($memberBuildingFees->getBalance());

            $entityManager->persist($operationFees);

            /*Update the ledger card first step*/ 
            $ledgerBalanceBuildingFees = new GeneralLedgerBalance();
            $ledgerBalanceBuildingFees->setTypeOperation(Operation::TYPE_CASH_IN);
            $ledgerBalanceBuildingFees->setDebit($member->getBuildingFees());
            $ledgerBalanceBuildingFees->setCurrentUser($currentUser);
            $latestEntryGBL = $entityManager->getRepository('ReportBundle:GeneralLedgerBalance')->findOneBy(
                [],
                ['id' => 'DESC']);
            if ($latestEntryGBL) {
                $ledgerBalanceBuildingFees->setBalance($latestEntryGBL->getBalance() + $member->getBuildingFees());
            }else{
                $ledgerBalanceBuildingFees->setBalance($member->getBuildingFees());
            }
            $ledgerBalanceBuildingFees->setAccount($memberBuildingFees);
            $ledgerBalanceBuildingFees->setRepresentative($member->getName());
            $ledgerBalanceBuildingFees->setAccountBalance($memberBuildingFees->getBalance());
            $ledgerBalanceBuildingFees->setAccountTitle($memberBuildingFees->getAccountName()." A/C_".$member->getMemberNumber());
            $ledgerBalanceBuildingFees->setMember($member);
            /*Make record*/ 
            $entityManager->persist($ledgerBalanceBuildingFees);
            $entityManager->flush();
        }
            $entityManager->flush();

        return json_encode([
            "message" => "The member has bee saved successfully %", 
            "params" => $memberJSON, 
            "status" => "success"
        ]);
    }

    /**
     * @Route("/close", name="member_status")
     * @Method({"GET", "POST"})
     */
    public function databaseBackupAction(Request $request){
        $logger = $this->get('logger');

        try{
            $dataJSON = json_decode(json_encode($request->request->get('data')), true);
            $em = $this->getDoctrine()->getManager();
            $member = $em->getRepository('MemberBundle:Member')->find($dataJSON['idMember']);

            switch ($dataJSON['choice']) {
                case 1:
                    $member->setIsAproved(false);
                    return json_encode([
                        "message" => "The account has been closed succeffully", 
                        "status" => "success"
                    ]);
                    break;
                case 2:
                    $member->setIsAproved(true);
                    return json_encode([
                        "message" => "The account has been reopen succesfully",
                        "status" => "success"
                    ]);
                    break;
                default:
                    # code...
                    break;
            }
           
            }catch(Exception $ex){
                $logger("AN ERROR OCCURED");
                return json_encode([
                    "status" => "failed"
                ]);
            }
        }
}
