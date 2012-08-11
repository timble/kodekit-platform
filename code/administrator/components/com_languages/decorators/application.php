<?php
class ComLanguagesDecoratorApplication extends KObjectDecorator
{
	/**
	 * Proxy the application getRouter() method
	 *
	 * @param  array	$options 	An optional associative array of configuration settings.
	 * @return object NookuProxyRouter.
	 */
	public function getRouter($name = null, array $options = array())
	{
		$router = $this->getObject()->getRouter();
			
		if($router instanceof JRouter)
		{
			$router = $this->getService('com://admin/languages.decorator.router', array(
				'mode'   => $router->getMode(),
    			'vars'   => $router->getVars(),
			    'object' => $router
			));
		}
		
		return $router;
	}
	
	/**
	 * Proxy the application route() method
	 */
	public function route()
	{
		// get the full request URI
		$uri = clone(JURI::getInstance());

		$router = $this->getRouter();
		$result = $router->parse($uri);

		JRequest::set($result, 'get', true);
		
		parent::route();
	}
}