<?php

namespace ClassBundle\Controller;

use ClassBundle\Entity\Classe;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

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
     * @Security("is_granted('IS_AUTHENTICATED_FULLY')")
     * @Method("GET")
     */
    public function index()
    {
        $em = $this->getDoctrine()->getManager();
        $class = new Classe();
        $formClass = $this->createForm('ClassBundle\Form\ClasseType', $class);

        $class = $em->getRepository('ClassBundle:Classe')->findAll();

        return $this->render('classe/index.html.twig', array(
            'classes' => $class,
            'formClass' => $formClass->createView(),
        ));
    }

    /**
     * Creates a new classe entity.
     *
     * @Route("/new", name="classe_new")
     * @Method({"GET", "POST"})
     * @param Request $request
     * @Security("is_granted('IS_AUTHENTICATED_FULLY')")
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
     */
    public function create(Request $request)
    {
        $class = new Classe();
        $form = $this->createForm('ClassBundle\Form\ClasseType', $class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($class);
            $em->flush();

            return $this->redirectToRoute('classe_index');
        }
        return $this->render('classe/new.html.twig', array(
            'classe' => $class,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a classe entity.
     *
     * @Route("/{id}", name="classe_show")
     * @Method("GET")
     * @param Classe $class
     * @Security("is_granted('IS_AUTHENTICATED_FULLY')")
     * @return Response
     */
    public function showAction(Classe $class)
    {
        $deleteForm = $this->createDeleteForm($class);

        return $this->render('classe/show.html.twig', array(
            'classe' => $class,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing classe entity.
     *
     * @Route("/{id}/edit", name="classe_edit")
     * @Method({"GET", "POST"})
     * @param Request $request
     * @param Classe $class
     * @Security("is_granted('IS_AUTHENTICATED_FULLY')")
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
     */
    public function editAction(Request $request, Classe $class)
    {
        $deleteForm = $this->createDeleteForm($class);
        $editForm = $this->createForm('ClassBundle\Form\ClasseType', $class);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('classe_edit', array('id' => $class->getId()));
        }

        return $this->render('classe/edit.html.twig', array(
            'classe' => $class,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a classe entity.
     *
     * @Route("/{id}", name="classe_delete")
     * @Method("DELETE")
     * @param Request $request
     * @param Classe $class
     * @Security("is_granted('IS_AUTHENTICATED_FULLY')")
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function deleteAction(Request $request, Classe $class)
    {
        $form = $this->createDeleteForm($class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($class);
            $em->flush();
        }

        return $this->redirectToRoute('classe_index');
    }

    /**
     * Creates a form to delete a classe entity.
     *
     * @param Classe $class The classe entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Classe $class)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('classe_delete', array('id' => $class->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }


    /**
     * @param Request $request [contains the http request that is passed on]
     *
     * @Route("/new_json", name="classe_new_json")
     * @Method({"GET", "POST"})
     * @return Response
     */
    function addNewClassFromJSON(Request $request){

        $entityManager = $this->getDoctrine()->getManager();

        $class = new Classe();

        try{
            //first thing we get the classe with the JSON format
            $classJSON = json_decode(json_encode($request->request->get('data')), true);


            $class->setName($classJSON["name"]);
            $class->setDescription($classJSON["description"]);
            $class->setTotalAmount(0);

            if ($classJSON['classCategory'] == "") {
                
            }else{
                $classMere = $entityManager->getRepository('ClassBundle:Classe')->find($classJSON["classCategory"]);
                $class->setClassCategory($classMere);
            }


        /**
         * making recordds here
         * --------------------
         */
        
        $entityManager->persist($class);
        $entityManager->flush();
       
        

        }catch(Exception $ex){

            $response["success"] = false;
        }

        // $response["message"]             =
        $response["data"]               = $classJSON;
        $response["optionalData"]       = json_encode((array)$class->getName());

        //we say everything went well
        $response["success"] = true;

        return new Response(json_encode($response));
    }
}