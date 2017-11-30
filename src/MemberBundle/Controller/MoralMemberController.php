<?php

namespace MemberBundle\Controller;

use MemberBundle\Entity\MoralMember;
use MemberBundle\Entity\Representant;
use ConfigBundle\Entity\TransactionIncome;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Moralmember controller.
 *
 * @Route("moralmember")
 */
class MoralMemberController extends Controller
{
    /**
     * Lists all moralMember entities.
     *
     * @Route("/", name="moralmember_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $moralMembers = $em->getRepository('MemberBundle:MoralMember')->findAll();

        return $this->render('moralmember/index.html.twig', array(
            'moralMembers' => $moralMembers,
        ));
    }

    /**
     * Creates a new moralMember entity.
     *
     * @Route("/new", name="moralmember_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        $moralMember = new Moralmember();
        $representant = new Representant();
        $form = $this->createForm('MemberBundle\Form\MoralMemberType', $moralMember);
        $reForm = $this->createForm('MemberBundle\Form\RepresentantType', $representant);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($moralMember);
            $em->flush();

            return $this->redirectToRoute('moralmember_show', array('id' => $moralMember->getId()));
        }

        return $this->render('moralmember/new.html.twig', array(
            'moralMember' => $moralMember,
            'reForm' => $reForm->createView(),
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a member entity.
     *
     * @Route("/{id}/receipt", name="moralmember_registration_receipt")
     * @Method("GET")
     */
    public function memberRegistrationReceiptAction(MoralMember $member){

        $em = $this->getDoctrine()->getManager();
        $agency = $em->getRepository('ConfigBundle:Agency')->find(1);

        $memberName = str_replace(' ', '_', $member->getSocialReason());


        $html =  $this->renderView('moralmember/moral_registration_fees_receipt_file.html.twig', array(
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
     * Finds and displays a moralMember entity.
     *
     * @Route("/{id}", name="moralmember_show")
     * @Method("GET")
     */
    public function showAction(MoralMember $moralMember)
    {
        $deleteForm = $this->createDeleteForm($moralMember);
        $em = $this->getDoctrine()->getManager();
        $representants = $em->getRepository('MemberBundle:Representant')->findByIdMember($moralMember);

        return $this->render('moralmember/show.html.twig', array(
            'moralMember' => $moralMember,
            'representants' => $representants,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing moralMember entity.
     *
     * @Route("/{id}/edit", name="moralmember_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, MoralMember $moralMember)
    {
        $deleteForm = $this->createDeleteForm($moralMember);
        $editForm = $this->createForm('MemberBundle\Form\MoralMemberType', $moralMember);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('moralmember_edit', array('id' => $moralMember->getId()));
        }

        return $this->render('moralmember/edit.html.twig', array(
            'moralMember' => $moralMember,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a moralMember entity.
     *
     * @Route("/{id}", name="moralmember_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, MoralMember $moralMember)
    {
        $form = $this->createDeleteForm($moralMember);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($moralMember);
            $em->flush();
        }

        return $this->redirectToRoute('moralmember_index');
    }

    /**
     * Creates a form to delete a moralMember entity.
     *
     * @param MoralMember $moralMember The moralMember entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(MoralMember $moralMember){
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('moralmember_delete', array('id' => $moralMember->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }


    /**
     * @param Request $request [contains the http request that is passed on]
     * 
     * @Route("/new_json", name="moralmember_new_json")
     * @Method({"GET", "POST"})
     */
    function addNewMoralMemberFromJSON(Request $request){

        $entityManager = $this->getDoctrine()->getManager();

        $logger = $this->get('logger');

        $moralMember = new MoralMember();

        $currentMoralMemberID = 0;

        try{
            //first thing we get the member with the JSON format
            $memberJSON = json_decode(json_encode($request->request->get('data')), true);


            $moralMember->setSocialReason($memberJSON["socialReason"]);
            $moralMember->setDateOfCreation(new \DateTime($memberJSON["dateOfCreation"]));
            $moralMember->setAddress($memberJSON["address"]);
            $moralMember->setProposedBy($memberJSON["proposedBy"]);
            $moralMember->setIsAproved($memberJSON["isAproved"]);
            $moralMember->setAprovedBy($memberJSON["aprovedBy"]);
            $moralMember->setMemberNumber($memberJSON["memberNumber"]);
            $moralMember->setDoneAt($memberJSON["doneAt"]);
            $moralMember->setMembershipDateCreation(new \DateTime($memberJSON["membershipDateCreation"]));
            $moralMember->setWitnessName($memberJSON["witnessName"]);
            $moralMember->setPhoneNumber($memberJSON["phoneNumber"]);
            $moralMember->setRegistrationFees($memberJSON["registrationFees"]);


            /*register the register income in the month income*/

            $income = new TransactionIncome();

            $income->setAmount($memberJSON["registrationFees"]);
            $income->setDescription("Member Registration fees. Member number: ".$moralMember->getMemberNumber()." // Member Social Reason: ".$moralMember->getSocialReason()." // Amount: ".$income->getAmount());

        /**
         * making recordds here
         * --------------------
         */
        
        $entityManager->persist($moralMember);
        $entityManager->persist($income);

        $entityManager->flush();
       
        $currentMoralMemberID = $moralMember->getId();
        

        }catch(Exception $ex){

            $logger("AN ERROR OCCURED");
            $response["success"] = false;
        }

        /**
         * At this point we need to register all the dependant objects
         * ---------------------
         */
        
        try{

            foreach ($memberJSON["representants"] as $key => $value) {

                $representant = new Representant();

                $representant->setIdMember($moralMember);
                $representant->setName($value['name']);
                $representant->setNicNumber($value['nicNumber']);


                $entityManager->persist($representant);
                $entityManager->flush();
                
            }
        }catch(Exception $ex){
            $logger->error('SOMETHING WENT WRONG : MemberController : trying to insert Represantant');
            #TODO : here we roll back eveything in case it fails
        }

        // $reponse["message"]             = 
        $response["data"]               = $memberJSON;
        $response["optionalData"]       = json_encode((array)$moralMember->getMemberNumber());

        //we say everything went well
        $response["success"] = true;

        return new Response(json_encode($response));
    }
}
