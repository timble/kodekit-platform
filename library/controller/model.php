<?php
/**
 * Nooku Framework - http://www.nooku.org
 *
 * @copyright	Copyright (C) 2007 - 2013 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		git://git.assembla.com/nooku-framework.git for the canonical source repository
 */

namespace Nooku\Library;

/**
 * Abstract Model Controller
 *
 * @author  Johan Janssens <http://nooku.assembla.com/profile/johanjanssens>
 * @package Nooku\Library\Controller
 */
abstract class ControllerModel extends ControllerView implements ControllerModellable
{
    /**
     * Model object or identifier
     *
     * @var	string|object
     */
    protected $_model;

    /**
     * Constructor
     *
     * @param ObjectConfig $config 	An optional ObjectConfig object with configuration options.
     */
    public function __construct(ObjectConfig $config)
    {
        parent::__construct($config);

        // Set the model identifier
        $this->_model = $config->model;
    }

    /**
     * Initializes the default configuration for the object
     *
     * Called from {@link __construct()} as a first step of object instantiation.
     *
     * @param ObjectConfig $config 	An optional ObjectConfig object with configuration options.
     * @return void
     */
    protected function _initialize(ObjectConfig $config)
    {
        $toolbars = array();
        if($config->dispatched && $config->user->isAuthentic()) {
            $toolbars[] = $this->getIdentifier()->name;
        }

        $config->append(array(
            'toolbars'   => $toolbars,
            'model'	     => $this->getIdentifier()->name,
        ));

        parent::_initialize($config);
    }

    /**
     * Get the view object attached to the controller
     *
     * @return	ViewInterface
     */
    public function getView()
    {
        if(!$this->_view instanceof ViewInterface)
        {
            if(!$this->_view instanceof ObjectIdentifier)
            {
                if(!$this->getRequest()->query->has('view'))
                {
                    $view = $this->getIdentifier()->name;

                    if($this->getModel()->getState()->isUnique()) {
                        $view = StringInflector::singularize($view);
                    } else {
                        $view = StringInflector::pluralize($view);
                    }
                }
                else $view = $this->getRequest()->query->get('view', 'cmd');

                //Set the view
                $this->setView($view);
            }

            //Get the view
            $view = parent::getView();

            //Set the model in the view
            $view->setModel($this->getModel());
        }

        return parent::getView();
    }

    /**
     * Get the model object attached to the controller
     *
     * @throws	\UnexpectedValueException	If the model doesn't implement the ModelInterface
     * @return	ModelAbstract
     */
    public function getModel()
    {
        if(!$this->_model instanceof ModelInterface)
        {
            if(!($this->_model instanceof ObjectIdentifier)) {
                $this->setModel($this->_model);
            }

            $this->_model = $this->getObject($this->_model);

            //Inject the request into the model state
            $this->_model->setState($this->getRequest()->query->toArray());

            if(!$this->_model instanceof ModelInterface)
            {
                throw new \UnexpectedValueException(
                    'Model: '.get_class($this->_model).' does not implement ModelInterface'
                );
            }
        }

        return $this->_model;
    }

    /**
     * Method to set a model object attached to the controller
     *
     * @param	mixed	$model An object that implements ObjectInterface, ObjectIdentifier object
     * 					       or valid identifier string
     * @return	ControllerView
     */
    public function setModel($model)
    {
        if(!($model instanceof ModelInterface))
        {
            if(is_string($model) && strpos($model, '.') === false )
            {
                // Model names are always plural
                if(StringInflector::isSingular($model)) {
                    $model = StringInflector::pluralize($model);
                }

                $identifier			= clone $this->getIdentifier();
                $identifier->path	= array('model');
                $identifier->name	= $model;
            }
            else $identifier = $this->getIdentifier($model);

            $model = $identifier;
        }

        $this->_model = $model;

        return $this;
    }

    /**
     * Render action
     *
     * This function translates a render request into a read or browse action. If the view name is singular a read
     * action will be executed, if plural a browse action will be executed.
     *
     * @param	CommandContext	$context A command context object
     * @return 	string|false 	The rendered output of the view or FALSE if something went wrong
     */
    protected function _actionRender(CommandContext $context)
    {
        //Check if we are reading or browsing
        $action = StringInflector::isSingular($this->getView()->getName()) ? 'read' : 'browse';

        //Execute the action
        $this->execute($action, $context);

        return parent::_actionRender($context);
    }

	/**
	 * Generic browse action, fetches a list
	 *
	 * @param	CommandContext	$context A command context object
	 * @return 	DatabaseRowsetInterface A rowset object containing the selected rows
	 */
	protected function _actionBrowse(CommandContext $context)
	{
		$entity = $this->getModel()->getRowset();
		return $entity;
	}

	/**
	 * Generic read action, fetches an item
	 *
	 * @param	CommandContext	$context A command context object
	 * @return 	DatabaseRowInterface A row object containing the selected row
	 */
	protected function _actionRead(CommandContext $context)
	{
	    $entity = $this->getModel()->getRow();
	    $name   = ucfirst($this->getView()->getName());

		if($this->getModel()->getState()->isUnique() && $entity->isNew()) {
		    throw new ControllerExceptionNotFound($name.' Not Found');
		}

		return $entity;
	}

	/**
	 * Generic edit action, saves over an existing item
	 *
	 * @param	CommandContext	$context A command context object
     * @throws  ControllerExceptionNotFound   If the entity could not be found
	 * @return 	DatabaseRow(set)Interface A row(set) object containing the updated row(s)
	 */
	protected function _actionEdit(CommandContext $context)
	{
	    $entity = $this->getModel()->getData();

	    if(count($entity))
	    {
	        $entity->setData($context->request->data->toArray());

	        //Only set the reset content status if the action explicitly succeeded
	        if($entity->save() === true) {
		        $context->response->setStatus(self::STATUS_RESET);
		    } else {
		        $context->response->setStatus(self::STATUS_UNCHANGED);
		    }
		}
		else throw new ControllerExceptionNotFound('Entity could not be found');

		return $entity;
	}

	/**
	 * Generic add action, saves a new item
	 *
	 * @param	CommandContext	$context A command context object
     * @throws  ControllerExceptionActionFailed If the delete action failed on the data entity
     * @throws  ControllerExceptionBadRequest   If the entity already exists
	 * @return 	DatabaseRowInterface   A row object containing the new data
	 */
	protected function _actionAdd(CommandContext $context)
	{
		$entity = $this->getModel()->getRow();

		if($entity->isNew())
		{
		    $entity->setData($context->request->data->toArray());

		    //Only throw an error if the action explicitly failed.
		    if($entity->save() === false)
		    {
			    $error = $entity->getStatusMessage();
		        throw new ControllerExceptionActionFailed($error ? $error : 'Add Action Failed');
		    }
		    else $context->response->setStatus(self::STATUS_CREATED);
		}
		else throw new ControllerExceptionBadRequest('Entity Already Exists');

		return $entity;
	}

	/**
	 * Generic delete function
	 *
	 * @param	CommandContext	$context A command context object
     * @throws  ControllerExceptionActionFailed 	If the delete action failed on the data entity
	 * @return 	DatabaseRow(set)Interface A row(set) object containing the deleted row(s)
	 */
	protected function _actionDelete(CommandContext $context)
	{
	    $entity = $this->getModel()->getData();

        if($entity instanceof DatabaseRowsetInterface)  {
            $count = count($entity);
        } else {
            $count = (int) !$entity->isNew();;
        }

		if($count)
	    {
            $entity->setData($context->request->data->toArray());

            //Only throw an error if the action explicitly failed.
	        if($entity->delete() === false)
	        {
			    $error = $entity->getStatusMessage();
                throw new ControllerExceptionActionFailed($error ? $error : 'Delete Action Failed');
		    }
		    else $context->response->setStatus(self::STATUS_UNCHANGED);
		}
		else throw new ControllerExceptionNotFound('Entity Not Found');

		return $entity;
	}

    /**
     * Supports a simple form Fluent Interfaces. Allows you to set the request properties by using the request property
     * name as the method name.
     *
     * For example : $controller->limit(10)->browse();
     *
     * @param	string	$method Method name
     * @param	array	$args   Array containing all the arguments for the original call
     * @return	ControllerView
     *
     * @see http://martinfowler.com/bliki/FluentInterface.html
     */
    public function __call($method, $args)
    {
        //Check first if we are calling a mixed in method to prevent the model being
        //loaded during object instantiation.
        if(!isset($this->_mixed_methods[$method]))
        {
            //Check for model state properties
            if(isset($this->getModel()->getState()->$method))
            {
                $this->getRequest()->query->set($method, $args[0]);
                $this->getModel()->getState()->set($method, $args[0]);

                return $this;
            }
        }

        return parent::__call($method, $args);
    }
}