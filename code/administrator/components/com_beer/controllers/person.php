<?php
class BeerControllerPerson extends KControllerForm
{
	public function __construct($options = array())
	{
		parent::__construct($options);

		$this->registerFilterBefore('add'   , 'filterCreated');
	}

	public function filterCreated($args)
	{
		$user = KFactory::get('lib.joomla.user');

		KRequest::set('post.created_by', $user->get('id'));
	}
}