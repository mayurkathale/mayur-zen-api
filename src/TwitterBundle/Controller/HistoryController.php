<?php

namespace TwitterBundle\Controller;

use TwitterBundle\Entity\History;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;

/**
 * History controller.
 *
 * @Route("history")
 */
class HistoryController extends Controller
{
    /**
     * Lists all history entities.
     *
     * @Route("/", name="history_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $histories = $em->getRepository('TwitterBundle:History')->findAll();

        return $this->render('history/index.html.twig', array(
            'histories' => $histories,
        ));
    }

    /**
     * Creates a new history entity.
     *
     * @Route("/new", name="history_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        $history = new History();
        $form = $this->createForm('TwitterBundle\Form\HistoryType', $history);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($history);
            $em->flush();

            return $this->redirectToRoute('history_show', array('id' => $history->getId()));
        }

        return $this->render('history/new.html.twig', array(
            'history' => $history,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a history entity.
     *
     * @Route("/{id}", name="history_show")
     * @Method("GET")
     */
    public function showAction(History $history)
    {
        $deleteForm = $this->createDeleteForm($history);

        return $this->render('history/show.html.twig', array(
            'history' => $history,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing history entity.
     *
     * @Route("/{id}/edit", name="history_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, History $history)
    {
        $deleteForm = $this->createDeleteForm($history);
        $editForm = $this->createForm('TwitterBundle\Form\HistoryType', $history);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('history_edit', array('id' => $history->getId()));
        }

        return $this->render('history/edit.html.twig', array(
            'history' => $history,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a history entity.
     *
     * @Route("/{id}", name="history_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, History $history)
    {
        $form = $this->createDeleteForm($history);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($history);
            $em->flush();
        }

        return $this->redirectToRoute('history_index');
    }

    /**
     * Creates a form to delete a history entity.
     *
     * @param History $history The history entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(History $history)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('history_delete', array('id' => $history->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}
