<?php
/**
 * @version		$Id$
 * @category	Koowa
 * @package     Koowa_Controller
 * @copyright	Copyright (C) 2007 - 2010 Johan Janssens. All rights reserved.
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link     	http://www.nooku.org
 */

/**
 * Action Controller Class
 *
 * @author		Johan Janssens <johan@nooku.org>
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
	 * Store the referrer in the session
	 *
	 * @param 	KCommandContext		The active command context
	 * @return void
	 */
	public function saveReferrer(KCommandContext $context)
	{								
		if($referrer = KRequest::referrer())
		{
			$request  = KRequest::url();
			
			$request->get(KHttpUri::PART_PATH | KHttpUri::PART_QUERY);
			$referrer->get(KHttpUri::PART_PATH | KHttpUri::PART_QUERY);
		
			//Compare request url and referrer
			if($request != $referrer) {
				KRequest::set('session.com.controller.referrer', (string) $referrer);
			}
		}
	}

	/**
	 * Display the view
	 *
	 * @param 	KCommandContext		The active command context
	 * @return void
	 */
	public function displayView(KCommandContext $context)
	{
		$view = $this->getView();
		
		if($view instanceof KViewTemplate && isset($this->_request->layout)) {
			$view->setLayout($this->_request->layout);
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
			$identifier->name	= KRequest::format() ? KRequest::format() : 'html';
			
			$this->_view = KFactory::get($identifier);
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
		if(!($view instanceof KViewAbstract))
		{
			$identifier = KFactory::identify($view);

			if($identifier->path[0] != 'view') {
				throw new KControllerException('Identifier: '.$identifier.' is not a view identifier');
			}

			$this->_view = $view;
		}
		
		$this->_view = $view;
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
	 * @param	KCommandContext	A command context object
	 * @return 	KDatabaseRow	A row object containing the selected row
	 */
	protected function _actionRead(KCommandContext $context)
	{
		$row = parent::_actionRead($context);
		
		if(isset($row))
		{
			//Lock the row
			if($this->_request->layout == 'form' && $row->isLockable()) {
				$row->lock();
			}
		}

		return $row;
	}

	/**
	 * Generic save action
	 *
	 * @param   KCommandContext	A command context object
	 * @return 	KDatabaseRow 	A row object containing the saved data
	 */
	protected function _actionSave(KCommandContext $context)
	{
		if($this->getModel()->getState()->isUnique())
		{
			//Edit returns a rowset
			$rowset = $this->execute('edit', $context);

			// Get the row based on the identity key
			$row = $rowset->find($this->getModel()->getState()->id);

			//Unlock the row
			if($row->isLockable()) {
				$row->unlock();
			}

		} else $row = $this->execute('add', $context);
		
		//Create the redirect
		$this->_redirect = KRequest::get('session.com.controller.referrer', 'url');
		return $row;
	}

	/**
	 * Generic apply action
	 *
	 * @param	KCommandContext	A command context object
	 * @return 	KDatabaseRow 	A row object containing the saved data
	 */
	protected function _actionApply(KCommandContext $context)
	{
		if($this->getModel()->getState()->isUnique())
		{
			//Edit returns a rowset
			$rowset = $this->execute('edit', $context);
			
			// Get the row based on the identity key
			$row = $rowset->find($this->getModel()->getState()->id);

			//Unlock the row
			if($row->isLockable()) {
				$row->unlock();
			}
		}
		else $row = $this->execute('add', $context);
		
		//Create the redirect
		$this->_redirect = KRequest::url();
		
		return $row;
	}

	/**
	 * Generic cancel action
	 *
	 * @param	KCommandContext	A command context object
	 * @return 	KDatabaseRow	A row object containing the data of the cancelled object
	 */
	protected function _actionCancel(KCommandContext $context)
	{
		//Don't pass through the command chain
		$row = parent::_actionRead($context);

		if($row->isLockable()) {
			$row->unlock();
		}

		//Create the redirect
		$this->_redirect = KRequest::get('session.com.controller.referrer', 'url');
		return $row;
	}
	
	/**
	 * Generic display function
	 * 
	 * This function wraps around the read or browse action. If the model state is
	 * unique a read action will be executed, if not unique a browse action will be 
	 * executed.
	 *
	 * @param	KCommandContext	A command context object
	 * @return 	KDatabaseRow(set) 	A row(set) object containing the data to display
	 */
	protected function _actionDisplay(KCommandContext $context)
	{
		$action = $this->getModel()->getState()->isUnique() ? 'read' : 'browse';
		return $this->execute($action, $context);
	}
}