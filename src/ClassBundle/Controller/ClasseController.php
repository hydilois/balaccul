<?php

namespace ClassBundle\Controller;

use ClassBundle\Entity\Classe;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Classe controller.
 *
 * @Route("classe")
 */
class ClasseController extends Controller
{
    /**
     * Lists all classe entities.
     *
     * @Route("/list", name="classe_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();
        $classe = new Classe();
        $formClasse = $this->createForm('ClassBundle\Form\ClasseType', $classe);

        $classes = $em->getRepository('ClassBundle:Classe')->findAll();

        return $this->render('classe/index.html.twig', array(
            'classes' => $classes,
            'formClass' => $formClasse->createView(),
        ));
    }

    /**
     * Creates a new classe entity.
     *
     * @Route("/new", name="classe_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        $classe = new Classe();
        $form = $this->createForm('ClassBundle\Form\ClasseType', $classe);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($classe);
            $em->flush();

            return $this->redirectToRoute('classe_index');
        }
        return $this->render('classe/new.html.twig', array(
            'classe' => $classe,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a classe entity.
     *
     * @Route("/{id}", name="classe_show")
     * @Method("GET")
     */
    public function showAction(Classe $classe)
    {
        $deleteForm = $this->createDeleteForm($classe);

        return $this->render('classe/show.html.twig', array(
            'classe' => $classe,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing classe entity.
     *
     * @Route("/{id}/edit", name="classe_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, Classe $classe)
    {
        $deleteForm = $this->createDeleteForm($classe);
        $editForm = $this->createForm('ClassBundle\Form\ClasseType', $classe);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('classe_edit', array('id' => $classe->getId()));
        }

        return $this->render('classe/edit.html.twig', array(
            'classe' => $classe,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a classe entity.
     *
     * @Route("/{id}", name="classe_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, Classe $classe)
    {
        $form = $this->createDeleteForm($classe);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($classe);
            $em->flush();
        }

        return $this->redirectToRoute('classe_index');
    }

    /**
     * Creates a form to delete a classe entity.
     *
     * @param Classe $classe The classe entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Classe $classe)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('classe_delete', array('id' => $classe->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }


    /**
     * @param Request $request [contains the http request that is passed on]
     * 
     * @Route("/new_json", name="classe_new_json")
     * @Method({"GET", "POST"})
     */
    function addNewClasseFromJSON(Request $request){

        $entityManager = $this->getDoctrine()->getManager();

        $logger = $this->get('logger');

        $classe = new Classe();

        try{
            //first thing we get the classe with the JSON format
            $classeJSON = json_decode(json_encode($request->request->get('data')), true);


            $classe->setName($classeJSON["name"]);
            $classe->setDescription($classeJSON["description"]);
            $classe->setTotalAmount(0);

            if ($classeJSON['classCategory'] == "") {
                
            }else{
                $classeMere = $entityManager->getRepository('ClassBundle:Classe')->find($classeJSON["classCategory"]);
                $classe->setClassCategory($classeMere);
            }


        /**
         * making recordds here
         * --------------------
         */
        
        $entityManager->persist($classe);
        $entityManager->flush();
       
        

        }catch(Exception $ex){

            $logger("AN ERROR OCCURED");
            $response["success"] = false;
        }

        // $reponse["message"]             = 
        $response["data"]               = $classeJSON;
        $response["optionalData"]       = json_encode((array)$classe->getName());

        //we say everything went well
        $response["success"] = true;

        return new Response(json_encode($response));
    }
}