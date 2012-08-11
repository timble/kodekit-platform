<?php
require_once(JPATH_ADMINISTRATOR.DS.'includes'.DS.'router.php');

class ComLanguagesDecoratorRouter extends KObjectDecorator
{
	public function __construct(KConfig $config)
	{
		parent::__construct($config);
		
		if($config->vars) {
		    $this->_vars = $config->vars;
		}
		
		/*if(array_key_exists('vars', $config)) {
			$this->_vars = $config['vars'];
		}*/
	}

	/*public function parse($uri)
	{
		$nooku = KFactory::get('admin::com.nooku.model.nooku');
		$app   = KFactory::get('lib.joomla.application');
		
		// Perform the actual parse
		$result = parent::parse($uri);
		$this->setVars($result);
		
		// Redirect if the language has changed
		$old = $nooku->getLanguage();
		$new = KInput::get('lang', array('post', 'get'), 'lang');
		
		if(isset($new) && strtolower($new) !=  strtolower($old))
		{
			//Set the language
			$nooku->setLanguage($new);
				
			if(KInput::getMethod() == 'POST')
			{
				$uri->setVar('lang', $new);
				$route = JRoute::_($uri->toString(), false);
				
				/*
				 * Dirty hack. Joomla URI class transforms cid[] into cid[0]
				 * 
				 * TODO : either fix in KUri or in the koowa javascript uri parser
				 */
				/*$route =  str_replace('cid[0]', 'cid[]', $route);
				$app->redirect($route);
			}
		}
					
		return $result;
	}*/
	
	public function build($url)
	{
		return parent::build($url);
	}
}