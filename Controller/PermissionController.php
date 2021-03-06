<?php
/**
 * PermissionController.php file
 *
 * File that contains the permissions controller class
 *
 * Licence MIT
 * Copyright (c) 2014 Multnomah Education Service District <http://www.mesd.k12.or.us>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 * 
 * @filesource /src/Mesd/HelpWikiBundle/Controller/PermissionController.php
 * @package    Mesd\HelpWikiBundle\Controller
 * @copyright  2014 (c) Multnomah Education Service District <http://www.mesd.k12.or.us>
 * @license    <http://opensource.org/licenses/MIT> MIT
 * @author     Curtis G Hanson <chanson@mesd.k12.or.us>
 * @version    {@inheritdoc}
 */
namespace Mesd\HelpWikiBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Mesd\HelpWikiBundle\Entity\Permission;
use Mesd\HelpWikiBundle\Form\PermissionType;
use Mesd\HelpWikiBundle\Model\Menu;
/**
 * Permission controller.
 *
 */
class PermissionController extends Controller
{

    /**
     * Lists all Permission entities.
     *
     * @return unknown
     */
    public function listAction()
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('MesdHelpWikiBundle:Permission')->findAll();

        return $this->render('MesdHelpWikiBundle:Permission:list.html.twig', array(
            'entities' => $entities,
            'menu'     => new Menu(),
        ));
    }

    /**
     * Creates a new Permission entity.
     *
     * @param object  $request
     * @return unknown
     */
    public function createAction(Request $request)
    {
        $entity = new Permission();
        $form   = $this->createCreateForm($entity);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('MesdHelpWikiBundle_permission_view', array('id' => $entity->getId())));
        }

        return $this->render('MesdHelpWikiBundle:Permission:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
            'menu'   => new Menu(),
        ));
    }

    /**
     * Creates a form to create a Permission entity.
     *
     *
     * @param object  $entity The entity
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(Permission $entity)
    {
        $form = $this->createForm(new PermissionType(), $entity, array(
                'action' => $this->generateUrl('MesdHelpWikiBundle_permission_create'),
                'method' => 'POST',
        ));

        $form->add('submit', 'submit', array('label' => 'Create'));

        return $form;
    }

    /**
     * Displays a form to create a new Permission entity.
     *
     * @return unknown
     */
    public function newAction()
    {
        $entity = new Permission();
        $form   = $this->createCreateForm($entity);

        return $this->render('MesdHelpWikiBundle:Permission:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
            'menu'   => new Menu(),
        ));
    }

    /**
     * Finds and displays a Permission entity.
     *
     * @param unknown $id
     * @return unknown
     */
    public function viewAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('MesdHelpWikiBundle:Permission')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Permission entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return $this->render('MesdHelpWikiBundle:Permission:view.html.twig', array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),
            'menu'        => new Menu(),
        ));
    }

    /**
     * Displays a form to edit an existing Permission entity.
     *
     * @param unknown $id
     * @return unknown
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('MesdHelpWikiBundle:Permission')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Permission entity.');
        }

        $editForm   = $this->createEditForm($entity);
        $deleteForm = $this->createDeleteForm($id);

        return $this->render('MesdHelpWikiBundle:Permission:edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
            'menu'        => new Menu(),
        ));
    }

    /**
     * Creates a form to edit a Permission entity.
     *
     *
     * @param object  $entity The entity
     * @return \Symfony\Component\Form\Form The form
     */
    private function createEditForm(Permission $entity)
    {
        $form = $this->createForm(new PermissionType(), $entity, array(
            'action' => $this->generateUrl('MesdHelpWikiBundle_permission_update', array('id' => $entity->getId())),
            'method' => 'PUT',
        ));

        $form->add('submit', 'submit', array('label' => 'Update'));

        return $form;
    }

    /**
     * Edits an existing Permission entity.
     *
     * @param object  $request
     * @param unknown $id
     * @return unknown
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('MesdHelpWikiBundle:Permission')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Permission entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $em->flush();

            return $this->redirect($this->generateUrl('MesdHelpWikiBundle_permission_edit', array('id' => $id)));
        }

        return $this->render('MesdHelpWikiBundle:Permission:edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
            'menu'        => new Menu(),
        ));
    }

    /**
     * Deletes a Permission entity.
     *
     * @param object  $request
     * @param unknown $id
     * @return unknown
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('MesdHelpWikiBundle:Permission')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Permission entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('MesdHelpWikiBundle_permission_list'));
    }

    /**
     * Creates a form to delete a Permission entity by id.
     *
     *
     * @param mixed   $id The entity id
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id)
    {
        return $this
            ->createFormBuilder()
            ->setAction($this->generateUrl('MesdHelpWikiBundle_permission_delete', array('id' => $id)))
            ->setMethod('DELETE')
            ->add('submit', 'submit', array('label' => 'Delete'))
            ->getForm()
        ;
    }
}
