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
     * @Route("/{id}", name="member_show")
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

            $entityManager->persist($operationShare);

            $memberShares  = $entityManager->getRepository('ClassBundle:InternalAccount')->find(1);
            $memberShares->setCredit($memberShares->getCredit() + $member->getShare());
            $memberShares->setEndingBalance($memberShares->getCredit() - $memberShares->getDebit() + $memberShares->getBeginingBalance());

            $entityManager->persist($memberShares);
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

            $entityManager->persist($operationSaving);

            $memberSavings  = $entityManager->getRepository('ClassBundle:InternalAccount')->find(44);
            $memberSavings->setCredit($memberSavings->getCredit() + $member->getSaving());
            $memberSavings->setEndingBalance($memberSavings->getCredit() - $memberSavings->getDebit() + $memberSavings->getBeginingBalance());

            $entityManager->persist($memberSavings);
            $entityManager->flush();
        }

        if ($member->getDeposit() != 0) {//member depost is not null
            $operationDeposit = new Operation();
            $operationDeposit->setIsDeposit(true);
            $operationDeposit->setCurrentUser($currentUser);
            $operationDeposit->setAmount($member->getDeposit());
            $operationDeposit->setBalance($member->getDeposit());
            $operationDeposit->setTypeOperation(Operation::TYPE_CASH_IN);
            $operationDeposit->setMember($member);

            $entityManager->persist($operationDeposit);

            $memberDeposits  = $entityManager->getRepository('ClassBundle:InternalAccount')->find(42);
            $memberDeposits->setCredit($memberDeposits->getCredit() + $member->getDeposit());
            $memberDeposits->setEndingBalance($memberDeposits->getCredit() - $memberDeposits->getDebit() + $memberDeposits->getBeginingBalance());

            $entityManager->flush();
        }

        
        if ($member->getRegistrationFees() != 0) {//Member registration fees
            $memberEntranceFees  = $entityManager->getRepository('ClassBundle:InternalAccount')->find(151);
            $memberEntranceFees->setCredit($memberEntranceFees->getCredit() + $member->getRegistrationFees());
            $memberEntranceFees->setEndingBalance($memberEntranceFees->getCredit() - $memberEntranceFees->getDebit() + $memberEntranceFees->getBeginingBalance());

            $entityManager->persist($memberEntranceFees);
            $entityManager->flush();
        }

        if ($member->getBuildingFees() != 0) {//Member registration fees
            $memberBuildingFees  = $entityManager->getRepository('ClassBundle:InternalAccount')->find(6);
            $memberBuildingFees->setCredit($memberBuildingFees->getCredit() + $member->getBuildingFees());
            $memberBuildingFees->setEndingBalance($memberBuildingFees->getCredit() - $memberBuildingFees->getDebit() + $memberBuildingFees->getBeginingBalance());

            $entityManager->persist($memberBuildingFees);
            $entityManager->flush();
        }

        return json_encode([
            "message" => "The member has bee saved successfully %", 
            "params" => $memberJSON, 
            "status" => "success"
        ]);
    }
}
