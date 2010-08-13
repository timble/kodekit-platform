<?php
/**
 * @version		$Id$
 * @category	Koowa
 * @package     Koowa_Controller
 * @copyright	Copyright (C) 2007 - 2010 Johan Janssens and Mathias Verraes. All rights reserved.
 * @license		GNU GPLv2 <http://www.gnu.org/licenses/old-licenses/gpl-2.0.html>
 * @link     	http://www.koowa.org
 */

/**
 * Action Controller Class
 *
 * @author		Johan Janssens <johan@koowa.org>
 * @category	Koowa
 * @package     Koowa_Controller
 * @uses        KInflector
 */
abstract class KControllerView extends KControllerBread
{
	/**
	 * View identifier (APP::com.COMPONENT.view.NAME.FORMAT)
	 *
	 * @var	string|object
	 */
	protected $_view;

	/**
	 * URL for redirection.
	 *
	 * @var	string
	 */
	protected $_redirect = null;

	/**
	 * Redirect message.
	 *
	 * @var	string
	 */
	protected $_redirect_message = null;

	/**
	 * Redirect message type.
	 *
	 * @var	string
	 */
	protected $_redirect_type = 'message';

	/**
	 * Constructor
	 *
	 * @param 	object 	An optional KConfig object with configuration options.
	 */
	public function __construct(KConfig $config)
	{
		parent::__construct($config);

		 // Set the view identifier
		if(!empty($config->view)) {
			$this->setView($config->view);
		}

		//Register the displayView function in case we want to push the result of the
		//browse and read actions to the output stream
		if($config->auto_display)
		{
			$this->registerCallback('after.browse'  , array($this, 'displayView'))
				 ->registerCallback('after.read'    , array($this, 'displayView'));
		}

		$this->registerCallback('before.read', array($this, 'saveReferrer'));
		$this->registerCallback('before.browse', array($this, 'saveReferrer'));

		//Set default redirect
		$this->_redirect = KRequest::referrer();
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
        	'view'			=> null,
    		'auto_display'	=> true,
        ));

        parent::_initialize($config);
    }

	/**
	 * Check the token to prevent CSRF exploits before executing the action
	 *
	 * @param	string		The action to perform.
	 * @return	mixed|false The value returned by the called method, false in error case.
	 * @throws 	KControllerException
	 */
	public function execute($action, $data = null)
	{
		if(KRequest::method() != 'GET')
		{
			$req	= KRequest::get('request._token', 'md5');
       	 	$token	= JUtility::getToken();

        	if($req !== $token)
        	{
        		throw new KControllerException('Invalid token or session time-out.', KHttp::STATUS_UNAUTHORIZED);
        		return false;
        	}
		}

		return parent::execute($action, $data);
	}

	/**
	 * Store the referrer in the session
	 *
	 * @return void
	 */
	public function saveReferrer(KCommandContext $context)
	{
		$request  = KRequest::url();

		//Only save the referrer if we are in a item view
		if(array_key_exists('view', $request->query) && KInflector::isSingular($request->query['view']))
		{
			$referrer = KRequest::referrer();

			//Prevent referrer getting lost at a subsequent read action
			foreach(array('option', 'view') as $var)
			{
				if(isset($referrer->query[$var]) && isset($request->query[$var]))
				{
					if($referrer->query[$var] != $request->query[$var]) {
						 KRequest::set('session.com.dispatcher.referrer', (string) $referrer);
						 break;
					}
				}
			}
		}
	}

	/**
	 * Display the view
	 *
	 * @return void
	 */
	public function displayView(KCommandContext $context)
	{
		$view = KFactory::get($this->getView());

		if($view instanceof KViewTemplate) {
			$view->setLayout(isset($this->_request->layout) ? $this->_request->layout : 'default');
		}

		$context->result = $view->display();
	}

	/**
	 * Get the identifier for the view with the same name
	 *
	 * @return	KIdentifierInterface
	 */
	public function getView()
	{
		if(!$this->_view)
		{
			if(!isset($this->_request->view))
			{
				$name = $this->_identifier->name;
				if($this->_action == 'browse') {
					$this->_request->view = KInflector::pluralize($name);
				}
				else $this->_request->view = $name;
			}

			$identifier			= clone $this->_identifier;
			$identifier->path	= array('view', $this->_request->view);
			$identifier->name	= isset($this->_request->format) ? $this->_request->format : 'html';

			$this->_view = $identifier;
		}

		return $this->_view;
	}

	/**
	 * Method to set a view object attached to the controller
	 *
	 * @param	mixed	An object that implements KObjectIdentifiable, an object that
	 *                  implements KIndentifierInterface or valid identifier string
	 * @throws	KDatabaseRowsetException	If the identifier is not a view identifier
	 * @return	KControllerAbstract
	 */
	public function setView($view)
	{
		$identifier = KFactory::identify($view);

		if($identifier->path[0] != 'view') {
			throw new KControllerException('Identifier: '.$identifier.' is not a view identifier');
		}

		$this->_view = $identifier;
		return $this;
	}

	/**
	 * Set a URL for browser redirection.
	 *
	 * @param	string URL to redirect to.
	 * @param	string	Message to display on redirect. Optional, defaults to
	 * 			value set internally by controller, if any.
	 * @param	string	Message type. Optional, defaults to 'message'.
	 * @return	KControllerAbstract
	 */
	public function setRedirect( $url, $msg = null, $type = 'message' )
	{
		$this->_redirect   		 = $url;
		$this->_redirect_message = $msg;
		$this->_redirect_type	 = $type;

		return $this;
	}

	/**
	 * Returns an array with the redirect url, the message and the message type
	 *
	 * @return array	Named array containing url, message and messageType, or null if no redirect was set
	 */
	public function getRedirect()
	{
		$result = array();

		if(!empty($this->_redirect))
		{
			$url = $this->_redirect;

			//Create the url if no full URL was passed
			if(strrpos($url, '?') === false) {
				$url = 'index.php?option=com_'.$this->_identifier->package.'&'.$url;
			}

			$result = array(
				'url' 		=> JRoute::_($url, false),
				'message' 	=> $this->_redirect_message,
				'type' 		=> $this->_redirect_type,
			);
		}

		return $result;
	}

	/**
	 * Display a single item
	 *
	 * @return KDatabaseRow	A row object containing the selected row
	 */
	protected function _actionRead()
	{
		$row = parent::_actionRead();

		if(isset($row))
		{
			//Lock the row
			if($this->_request->layout == 'form' && $row->isLockable()) {
				$row->lock();
			}
		}

		return $row;
	}

	/*
	 * Generic save action
	 *
	 *	@param	mixed 	Either a scalar, an associative array, an object
	 * 					or a KDatabaseRow
	 * @return KDatabaseRow 	A row object containing the saved data
	 */
	protected function _actionSave($data)
	{
		if(KFactory::get($this->getModel())->getState()->isUnique())
		{
			//Edit returns a rowset
			$rowset = $this->execute('edit', $data);

			// Get the row based on the identity key
			$row = $rowset->find(KFactory::get($this->getModel())->getState()->id);

			//Unlock the row
			if($row->isLockable()) {
				$row->unlock();
			}

		} else $row = $this->execute('add', $data);
		
		$this->_redirect = KRequest::get('session.com.dispatcher.referrer', 'url');
		return $row;
	}

	/*
	 * Generic apply action
	 *
	 *	@param	mixed 	Either a scalar, an associative array, an object
	 * 					or a KDatabaseRow
	 * @return 	KDatabaseRow 	A row object containing the saved data
	 */
	protected function _actionApply($data)
	{
		if(KFactory::get($this->getModel())->getState()->isUnique())
		{
			//Edit returns a rowset
			$rowset = $this->execute('edit', $data);
			
			// Get the row based on the identity key
			$row = $rowset->find(KFactory::get($this->getModel())->getState()->id);

			//Unlock the row
			if($row->isLockable()) {
				$row->unlock();
			}
		}
		else $row = $this->execute('add', $data);

		$this->_redirect = 'view='.$this->_identifier->name.'&id='.$row->id;
		return $row;
	}

	/*
	 * Generic cancel action
	 *
	 * @return 	void
	 */
	protected function _actionCancel()
	{
		//Don't pass through the command chain
		$row = parent::_actionRead();

		if($row->isLockable()) {
			$row->unlock();
		}

		$this->_redirect = KRequest::get('session.com.dispatcher.referrer', 'url');
		return $row;
	}
}