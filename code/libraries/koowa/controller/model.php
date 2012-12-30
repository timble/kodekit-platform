<?php
/**
 * @version		$Id$
 * @package		Koowa_Controller
 * @copyright	Copyright (C) 2007 - 2012 Johan Janssens. All rights reserved.
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link     	http://www.nooku.org
 */

/**
 * Abstract Model Controller Class
 *
 * @author		Johan Janssens <johan@nooku.org>
 * @package		Koowa_Controller
 */
abstract class KControllerModel extends KControllerView
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
     * @param 	object 	An optional KConfig object with configuration options.
     */
    public function __construct(KConfig $config)
    {
        parent::__construct($config);

        // Set the model identifier
        $this->_model = $config->model;

        if($this->isDispatched()) {
            $this->attachBehavior('editable');
        }
    }

    /**
     * Initializes the default configuration for the object
     *
     * Called from {@link __construct()} as a first step of object instantiation.
     *
     * @param 	object 	An optional KConfig object with configuration options.
     * @return void
     */
    protected function _initialize(KConfig $config)
    {
    	$config->append(array(
    		'behaviors'  => array('lockable'),
            'model'	     => $this->getIdentifier()->name,
        ));

        parent::_initialize($config);
    }

    /**
     * Get the view object attached to the controller
     *
     * @return	KViewInterface
     */
    public function getView()
    {
        if(!$this->_view instanceof KViewInterface)
        {
            if(!$this->getRequest()->query->has('view'))
            {
                $view = $this->getIdentifier()->name;

                if($this->getModel()->getState()->isUnique()) {
                    $view = KInflector::singularize($view);
                } else {
                    $view = KInflector::pluralize($view);
                }
            }
            else $view = $this->getRequest()->query->get('view', 'cmd');

            //Set the view
            $this->setView($view);

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
     * @throws	\UnexpectedValueException	If the model doesn't implement the KModelInterface
     * @return	KModelAbstract
     */
    public function getModel()
    {
        if(!$this->_model instanceof KModelInterface)
        {
            if(!($this->_model instanceof KServiceIdentifier)) {
                $this->setModel($this->_model);
            }

            $this->_model = $this->getService($this->_model);

            //Inject the request into the model state
            $state = $this->getRequest()->query->toArray();
            $this->_model->set($state);

            if(!$this->_model instanceof KModelInterface)
            {
                throw new \UnexpectedValueException(
                    'Model: '.get_class($this->_model).' does not implement KModelInterface'
                );
            }
        }

        return $this->_model;
    }

    /**
     * Method to set a model object attached to the controller
     *
     * @param	mixed	$model An object that implements KObjectServiceable, KServiceIdentifier object
     * 					       or valid identifier string
     * @return	KControllerView
     */
    public function setModel($model)
    {
        if(!($model instanceof KModelInterface))
        {
            if(is_string($model) && strpos($model, '.') === false )
            {
                // Model names are always plural
                if(KInflector::isSingular($model)) {
                    $model = KInflector::pluralize($model);
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
     * @param	KCommandContext	$context A command context object
     * @return 	string|false 	The rendered output of the view or FALSE if something went wrong
     */
    protected function _actionRender(KCommandContext $context)
    {
        //Check if we are reading or browsing
        $action = KInflector::isSingular($this->getView()->getName()) ? 'read' : 'browse';

        //Execute the action
        $this->execute($action, $context);

        return parent::_actionRender($context);
    }

	/**
	 * Generic browse action, fetches a list
	 *
	 * @param	KCommandContext	$context A command context object
	 * @return 	KDatabaseRowsetInterface A rowset object containing the selected rows
	 */
	protected function _actionBrowse(KCommandContext $context)
	{
		$entity = $this->getModel()->getRowset();
		return $entity;
	}

	/**
	 * Generic read action, fetches an item
	 *
	 * @param	KCommandContext	$context A command context object
	 * @return 	KDatabaseRowInterface A row object containing the selected row
	 */
	protected function _actionRead(KCommandContext $context)
	{
	    $entity = $this->getModel()->getRow();
	    $name   = ucfirst($this->getView()->getName());

		if($this->getModel()->getState()->isUnique() && $entity->isNew()) {
		    throw new KControllerExceptionNotFound($name.' Not Found');
		}

		return $entity;
	}

	/**
	 * Generic edit action, saves over an existing item
	 *
	 * @param	KCommandContext	$context A command context object
     * @throws  KControllerExceptionNotFound   If the resource could not be found
	 * @return 	KDatabaseRowsetInterface A rowset object containing the updated rows
	 */
	protected function _actionEdit(KCommandContext $context)
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
		else throw new KControllerExceptionNotFound('Resource could not be found');

		return $entity;
	}

	/**
	 * Generic add action, saves a new item
	 *
	 * @param	KCommandContext	$context A command context object
     * @throws  KControllerExceptionActionFailed If the delete action failed on the data entity
     * @throws  KControllerExceptionBadRequest   If the resource already exists
	 * @return 	KDatabaseRowInterface   A row object containing the new data
	 */
	protected function _actionAdd(KCommandContext $context)
	{
		$entity = $this->getModel()->getRow();

		if($entity->isNew())
		{
		    $entity->setData($context->request->data->toArray());

		    //Only throw an error if the action explicitly failed.
		    if($entity->save() === false)
		    {
			    $error = $entity->getStatusMessage();
		        throw new KControllerExceptionActionFailed($error ? $error : 'Add Action Failed');
		    }
		    else $context->response->setStatus(self::STATUS_CREATED);
		}
		else throw new KControllerExceptionBadRequest('Resource Already Exists');

		return $entity;
	}

	/**
	 * Generic delete function
	 *
	 * @param	KCommandContext	$context A command context object
     * @throws  KControllerExceptionActionFailed 	If the delete action failed on the data entity
	 * @return 	KDatabaseRowsetInterface A rowset object containing the deleted rows
	 */
	protected function _actionDelete(KCommandContext $context)
	{
	    $entity = $this->getModel()->getData();

		if(count($entity))
	    {
            $entity->setData($context->request->data->toArray());

            //Only throw an error if the action explicitly failed.
	        if($entity->delete() === false)
	        {
			    $error = $entity->getStatusMessage();
                throw new KControllerExceptionActionFailed($error ? $error : 'Delete Action Failed');
		    }
		    else $context->response->setStatus(self::STATUS_UNCHANGED);
		}
		else throw new KControllerExceptionNotFound('Resource Not Found');

		return $entity;
	}

    /**
     * Supports a simple form Fluent Interfaces. Allows you to set the request properties by using the request property
     * name as the method name.
     *
     * For example : $controller->limit(10)->browse();
     *
     * @param	string	Method name
     * @param	array	Array containing all the arguments for the original call
     * @return	KControllerView
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
                $this->getModel()->set($method, $args[0]);

                return $this;
            }
        }

        return parent::__call($method, $args);
    }
}