<?php

namespace UrlBundle\Controller;

use UrlBundle\Entity\View;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

/**
 * View controller.
 *
 */
class ViewController extends Controller
{
    /**
     * Lists all view entities.
     *
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $views = $em->getRepository('UrlBundle:View')->findAll();

        return $this->render('view/index.html.twig', array(
            'views' => $views,
        ));
    }

    /**
     * Creates a new view entity.
     *
     */
    public function newAction(Request $request)
    {
        $view = new View();
        $form = $this->createForm('UrlBundle\Form\ViewType', $view);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($view);
            $em->flush($view);

            return $this->redirectToRoute('view_show', array('id' => $view->getId()));
        }

        return $this->render('view/new.html.twig', array(
            'view' => $view,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a view entity.
     *
     */
    public function showAction(View $view)
    {
        $deleteForm = $this->createDeleteForm($view);

        return $this->render('view/show.html.twig', array(
            'view' => $view,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing view entity.
     *
     */
    public function editAction(Request $request, View $view)
    {
        $deleteForm = $this->createDeleteForm($view);
        $editForm = $this->createForm('UrlBundle\Form\ViewType', $view);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('view_edit', array('id' => $view->getId()));
        }

        return $this->render('view/edit.html.twig', array(
            'view' => $view,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a view entity.
     *
     */
    public function deleteAction(Request $request, View $view)
    {
        $form = $this->createDeleteForm($view);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($view);
            $em->flush($view);
        }

        return $this->redirectToRoute('view_index');
    }

    /**
     * Creates a form to delete a view entity.
     *
     * @param View $view The view entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(View $view)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('view_delete', array('id' => $view->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}
