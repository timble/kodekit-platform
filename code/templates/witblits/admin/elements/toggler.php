<?php
/**
 * @version		$Id$
 * @category	Nooku
 * @package		Nooku_Templates
 * @subpackage	Witblits
 * @copyright	Copyright (C) 2010 Timble CVBA and Contributors. (http://www.timble.net)
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		http://www.nooku.org
 */

/**
 * Toggler Element
 *
 * To use this, make a start xml param tag with the param and value set
 * And an end xml param tag without the param and value set
 * Everything between those tags will be included in the slide
 *
 * Available extra parameters:
 * param			The name of the reference parameter
 * value			a comma seperated list of value on which to show the elements
 */
class JElementToggler extends JElement
{
	/**
	 * Element name
	 *
	 * @access	protected
	 * @var		string
	 */
	var	$_name = 'Toggler';

	function fetchTooltip( $label, $description, &$node, $control_name, $name )
	{
		return;
	}

	function fetchElement( $name, $value, &$node, $control_name )
	{
		$option = JRequest::getCmd( 'option' );

		// do not place toggler stuff on JoomFish pages
		if ( $option == 'com_joomfish' ) { return; }

		$param =			$node->attributes( 'param' );
		$value =			$node->attributes( 'value' );
		$nofx =				$node->attributes( 'nofx' );
		$horz =				$node->attributes( 'horizontal' );
		$method =			$node->attributes( 'method' );
		$overlay =			$node->attributes( 'overlay' );
		$casesensitive =	$node->attributes( 'casesensitive' );

		$document =& JFactory::getDocument();

		if ( $param != '' ) 
		{
			$set_groups = explode( '|', $param );
			$set_values = explode( '|', $value );
			$ids = array();
			foreach ( $set_groups as $i => $group ) {
				$count = $i;
				if ( $count >= count( $set_values ) ) {
					$count = 0;
				}
				$values = explode( ',', $set_values[$count] );
				foreach ( $values as $val ) {
					$ids[] = $group.'.'.$val;
				}
			}
			$html = '<div id="'.rand( 1000000, 9999999 ).'___'.implode( '___', $ids ).'" class="nntoggler';
			if ( $nofx ) {
				$html .= ' nntoggler_nofx';
			}
			if ( $horz ) {
				$html .= ' nntoggler_horizontal';
			}
			if ( $method == 'and' ) {
				$html .= ' nntoggler_and';
			}
			if ( $overlay ) {
				$html .= ' nntoggler_overlay';
			}
			if ( $casesensitive ) {
				$html .= ' nntoggler_casesensitive';
			}
			$html .= '" style="visibility: hidden;">';
			$html .= '<table width="100%" class="paramlist admintable" cellspacing="0">';
			$html .= '<tr style="height:auto;"><td colspan="2" class="paramlist_value">';
			$random = rand( 100000, 999999 );
			$html .= '<div id="end-'.$random.'"></div><script type="text/javascript">NoNumberElementsHideTD( "end-'.$random.'" );</script>';
		} 
		else 
		{
			$random = rand( 100000, 999999 );
			$html = '<div id="end-'.$random.'"></div><script type="text/javascript">NoNumberElementsHideTD( "end-'.$random.'" );</script>';
			$html .= '</td></tr></table>';
			$html .= '</div>';
		}

		return $html;
	}
}