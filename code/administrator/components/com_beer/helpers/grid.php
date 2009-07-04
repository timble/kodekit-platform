<?php

class BeerHelperGrid extends KObject
{
	public static function state( $state=0)
	{
		$options = array();
		$options[] = KTemplate::loadHelper('select.option',  0, '- '.JText::_( 'Select State' ) .' -');
		$options[] = KTemplate::loadHelper('select.option',  1, JText::_( 'Enabled' ));
		$options[] = KTemplate::loadHelper('select.option',  -1, JText::_( 'Disabled'));

		return KTemplate::loadHelper('select.genericlist', $options, 'state', 'class="inputbox" size="1" onchange="submitform( );"', 'value', 'text', $state );
	}
}