<?php

class BeerHelperGrid extends KObject
{
	public static function state( $filter_state='*', $published='Published', $unpublished='Unpublished', $archived=NULL, $trashed=NULL )
	{
		$state[] = JHTML::_('select.option',  '', '- '. JText::_( 'Select State' ) .' -' );
		//Jinx : Why is this used ?
		//$state[] = JHTML::_('select.option',  '*', JText::_( 'Any' ) );
		$state[] = JHTML::_('select.option',  'P', JText::_( $published ) );
		$state[] = JHTML::_('select.option',  'U', JText::_( $unpublished ) );

		if ($archived) {
			$state[] = JHTML::_('select.option',  'A', JText::_( $archived ) );
		}

		if ($trashed) {
			$state[] = JHTML::_('select.option',  'T', JText::_( $trashed ) );
		}

		return JHTML::_('select.genericlist',   $state, 'filter_state', 'class="inputbox" size="1" onchange="submitform( );"', 'value', 'text', $filter_state );
	}
}