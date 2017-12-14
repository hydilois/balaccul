<?php

namespace MemberBundle\Controller;

use MemberBundle\Entity\Member;
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
    public function indexAction()
    {
        $entityManager = $this->getDoctrine()->getManager();

        $query  = $entityManager->createQueryBuilder()
                ->select('m, sh, sa, de')
                ->from('MemberBundle:Member', 'm')
                ->leftJoin('AccountBundle:Share', 'sh', 'WITH', 'm.id = sh.physicalMember')
                ->leftJoin('AccountBundle:Saving', 'sa', 'WITH', 'm.id = sa.physicalMember')
                ->leftJoin('AccountBundle:Deposit', 'de', 'WITH', 'm.id = de.physicalMember')
                ->orderBy('m.memberNumber')
                ->getQuery();

        $members = $query->getScalarResult();

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
    public function editAction(Request $request, Member $member)
    {
        $deleteForm = $this->createDeleteForm($member);
        $editForm = $this->createForm('MemberBundle\Form\MemberEditType', $member);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            
            $buildingFees = $this->getDoctrine()->getManager()->getRepository('ClassBundle:InternalAccount')->find(10);
            $buildingFees->setAmount($buildingFees->getAmount() + $member->getBuildingFees());

            $this->getDoctrine()->getManager()->persist($buildingFees);

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

        $currentMemberID = 0;

        try{
            //first thing we get the member with the JSON format
            $memberJSON = json_decode(json_encode($request->request->get('data')), true);


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


            /*register the register income in the month income*/

            $income = new TransactionIncome();

            $income->setAmount($memberJSON["registrationFees"]);
            $income->setDescription("Member Registration fees. Member number: ".$member->getMemberNumber()." // Member Name: ".$member->getName()." // Amount: ".$income->getAmount());

            //update the cash in hand
            $cashInHandAccount  = $entityManager->getRepository('ClassBundle:InternalAccount')->find(9);
            $cashInHandAccount->setAmount($cashInHandAccount->getAmount() + $memberJSON["registrationFees"]);

            $entityManager->persist($cashInHandAccount);


            /**
             * making recordds here
             * --------------------
             */
        
            $entityManager->persist($member);
            $entityManager->persist($income);

            $entityManager->flush();
       
            $currentMemberID = $member->getId();
        

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

                $beneficiary = new Beneficiary();

                $beneficiary->setIdMember($member);
                $beneficiary->setName($value['name']);
                $beneficiary->setRelation($value['relation']);
                $beneficiary->setRatio($value['ratio']);


                $entityManager->persist($beneficiary);
                $entityManager->flush();
                
            }
        }catch(Exception $ex){
            $logger->error('SOMETHING WENT WRONG : MemberController : trying to insert Beneficiary');
            #TODO : here we roll back eveything in case it fails
        }

        // $reponse["message"]             = 
        $response["data"]               = $memberJSON;
        $response["optionalData"]       = json_encode((array)$member->getMemberNumber());

        //we say everything went well
        $response["success"] = true;

        return new Response(json_encode($response));
    }
}
