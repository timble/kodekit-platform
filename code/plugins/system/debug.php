<?php
/** $Id$ */

class PlgApplicationDebug extends KCommand
{
	public function execute( $name, KCommandContext $context) 
	{
		return KDEBUG ? parent::execute($name, $context) : true;
	}
	
	public function _applicationAfterRender(KCommandContext $context)
	{
		//Prettify the output using Tidy filter (if available) and debug has been enabled
		$config =  array(
				'indent'            => true,
               	'indent-attributes' => true,
               	'wrap'              => 120,
		);
	
		$context->result = KFilter::factory('tidy', array('config' => $config))
					->sanitize((string) $context->result);
	}
}

