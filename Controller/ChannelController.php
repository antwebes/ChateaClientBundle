<?php

namespace Ant\Bundle\ChateaClientBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Ant\Bundle\ChateaClientBundle\Api\Model\Channel;
use Ant\Bundle\ChateaClientBundle\Api\Model\ChannelFilter;
use Ant\Bundle\ChateaClientBundle\Api\Model\ChannelType;
use Ant\Bundle\ChateaClientBundle\Form\ChannelFormType;
use Ant\Bundle\ChateaClientBundle\Form\ChannelFiltersFromType;

/**
 * Channel controller.
 *
 */
class ChannelController extends Controller
{

    private function getChannelRepository()
    {
        return $this->get('api_channels');
    }
    /**
     * Lists all Channels entities.
     *
     */
    public function indexAction()
    {

        $filter = $this->getRequest()->get('filter');
        $page = $this->getRequest()->get('page');

        $channelTypes = $this->get('api_channels_types')->findAll();
        $entity = new ChannelFilter();
        $form = $this->createForm(new ChannelFiltersFromType($channelTypes),$entity);
        $cleanFiler = null;

        $channelRepository = $this->getChannelRepository();

        $channelPager = $channelRepository->getPager();
        $channelPager->setPage($page);


        $channelPager->setFilter($cleanFiler);
        return $this->render('ChateaClientBundle:Channel:index.html.twig', array(
                'channelPager' => $channelPager,
                'filter_from' => $form->createView()
            )
        );

    }

    public function resetFilterAction($name)
    {
        $this->getChannelRepository()->disableFilter($name);
        return $this->redirect($this->generateUrl('antwebes_chateaclient_channel_show_all'));
    }

    /**
     * Finds and displays a Channel entity.
     *
     */
    public function showAction($channel_id)
    {
        if (!$channel_id) {
            throw $this->createNotFoundException('ChannelController::showAction() ERROR : Not found channel_id');
        }
        $channel = null;

        try{
            $entity = $this->getChannelRepository()->findById($channel_id);
        }catch (ApiException $ex)
        {
            $errors = $this->parseApiException($ex);

            return $this->render('ChateaClientBundle:Default:error.html.twig',
                array('errors' => $errors,
                    'return'=>$this->generateUrl('antwebes_chateaclient_channel_show', array('channel_id' => $channel_id))));

        }
        return $this->render('ChateaClientBundle:Channel:show.html.twig', array(
                'channel' => $entity
            )
        );
    }
    /**
     * Displays a form to create a new Channel entity.
     *
     */
    public function newAction()
    {

        $entity = new Channel();

        $form = $this->createForm(new ChannelFormType(), $entity);

        return $this->render('ChateaClientBundle:Channel:new.html.twig',
            array('form' => $form->createView()));

    }

    /**
     * Creates a new Oferta entity.
     *
     */
    public function createAction()
    {
        $entity = new Channel();

        $request = $this->getRequest();

        $form = $this->createForm(new ChannelFormType(), $entity);

        $form->handleRequest($request);

        if ($form->isValid()) {
            try{
                $this->getChannelRepository()->save($entity);
            }catch (ApiException $ex)
            {
                $errors = $this->parseApiException($ex);

                return $this->render('ChateaClientBundle:Default:error.html.twig',
                    array('errors' => $errors, 'return'=>$this->generateUrl('antwebes_chateaclient_channel_add')));

            }

            return $this->redirect($this->generateUrl('antwebes_chateaclient_channel_show', array('channel_id' => $entity->getId())));
        }

        return $this->render('ChateaClientBundle:Channel:new.html.twig', array(
                'entity' => $entity,
                'form' => $form->createView()
            ));

    }

    /**
     * Displays a form to edit an existing Channel entity.
     *
     */
    public function editAction($channel_id)
    {
        if (!$channel_id) {
            throw $this->createNotFoundException('ChannelController::showAction() ERROR : Not found channel_id');
        }

        try{
            $entity = $this->getChannelRepository()->findById($channel_id);
        }catch (\Exception $ex)
        {
            $errors = $this->parseApiException($ex);

            return $this->render('ChateaClientBundle:Default:error.html.twig',
                array('errors' => $errors, 'return'=>$this->generateUrl('antwebes_chateaclient_channel_show_all')));
        }

        $editForm = $this->createForm(new ChannelFormType(), $entity,array(
                'method' => 'PATCH',
            ));


        return $this->render('ChateaClientBundle:Channel:edit.html.twig', array(
                'entity' => $entity,
                'edit_form' => $editForm->createView()
            ));
    }

    /**
     * Edits an existing Channel entity.
     *
     */
    public function updateAction($channel_id)
    {

        $request = $this->getRequest();
        $editForm = null;

        try{
            $entity = $this->getChannelRepository()->findById($channel_id);

            $editForm = $this->createForm(new ChannelFormType(), $entity,array(
                    'method' => 'PATCH',
                ));

            $editForm->handleRequest($request);

            if ($editForm->isValid()) {

                $this->getChannelRepository()->update($entity);
                return $this->redirect($this->generateUrl('antwebes_chateaclient_channel_show', array('channel_id' => $entity->getId())));
            }
            return $this->render('ChateaClientBundle:Channel:edit.html.twig', array(
                    'entity' => $entity,
                    'edit_form' => $editForm->createView(),
                ));

        }catch (\Exception $ex)
        {
            $errors = $this->parseApiException($ex);

            return $this->render('ChateaClientBundle:Default:error.html.twig',
                array('errors' => $errors, 'return'=>$this->generateUrl('antwebes_chateaclient_channel_edit', array('channel_id' => $channel_id))));
        }

    }

    /**
     * Deletes a Channel entity.
     *
     */
    public function deleteAction($channel_id)
    {

        try{
                $this->getChannelRepository()->delete($channel_id);
            }catch (ApiException $ex)
            {
                $errors = $this->parseApiException($ex);

                return $this->render('ChateaClientBundle:Default:error.html.twig',
                    array('errors' => $errors, 'return'=>$this->generateUrl('antwebes_chateaclient_channel_show_all')));
            }

        return $this->redirect($this->generateUrl('antwebes_chateaclient_channel_show_all'));
    }

    /**
     * Lists all ChannelsTypes entities.
     *
     */
    public function showTypesAction()
    {
        $types = $this->getChannelTypeRepository()->findAll();
        return $this->render('ChateaClientBundle:Channel:showTypes.html.twig', array(
                'types' => $types
            )
        );
    }
}