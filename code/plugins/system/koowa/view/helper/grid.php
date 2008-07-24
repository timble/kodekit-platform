<?php
/**
 * @version		$Id$
 * @package		Koowa_View
 * @subpackage	Helper
 * @copyright	Copyright (C) 2007 - 2008 Joomlatools. All rights reserved.
 * @license		GNU GPLv2 <http://www.gnu.org/licenses/old-licenses/gpl-2.0.html>
 * @link     	http://www.koowa.org
 */

/**
 * Grid View Helper Class
 *
 * @author		Mathias Verraes <mathias@joomlatools.org>
 * @package		Koowa_View
 * @subpackage	Helper
 */
class KViewHelperGrid
{
	/**
	 * @param	string	The link title
	 * @param	string	The order field for the column
	 * @param	string	The current direction
	 * @param	string	The selected ordering
	 * @param	string	An optional task override
	 */
	public static function sort( $title, $order, $direction = 'asc', $selected = 0, $task=NULL )
	{
		$direction	= strtolower( $direction );
		$images		= array( 'sort_asc.png', 'sort_desc.png' );
		$index		= intval( $direction == 'desc' );
		$direction	= ($direction == 'desc') ? 'asc' : 'desc';

		$html = '<a href="javascript:tableOrdering(\''.$order.'\',\''.$direction.'\',\''.$task.'\');" title="'.JText::_( 'Click to sort this column' ).'">';
		$html .= JText::_( $title );
		if ($order == $selected ) {
			$html .= KViewHelper::_('image.administrator',  $images[$index], '/images/', NULL, NULL);
		}
		$html .= '</a>';
		return $html;
	}

	/**
	* @param int The row index
	* @param int The record id
	* @param boolean
	* @param string The name of the form element
	*
	* @return string
	*/
	public static function id( $rowNum, $recId, $checkedOut=false, $name='cid' )
	{
		if ( $checkedOut ) {
			return '';
		} else {
			return '<input type="checkbox" id="cb'.$rowNum.'" name="'.$name.'[]" value="'.$recId.'" onclick="isChecked(this.checked);" />';
		}
	}

	public static function access( &$row, $i, $archived = NULL )
	{
		if ( !$row->access )  {
			$color_access = 'style="color: green;"';
			$task_access = 'accessregistered';
		} else if ( $row->access == 1 ) {
			$color_access = 'style="color: red;"';
			$task_access = 'accessspecial';
		} else {
			$color_access = 'style="color: black;"';
			$task_access = 'accesspublic';
		}

		if ($archived == -1)
		{
			$href = JText::_( $row->groupname );
		}
		else
		{
			$href = '
			<a href="javascript:void(0);" onclick="return listItemTask(\'cb'. $i .'\',\''. $task_access .'\')" '. $color_access .'>
			'. JText::_( $row->groupname ) .'</a>'
			;
		}

		return $href;
	}

	public static function checkedOut( &$row, $i, $identifier = 'id' )
	{
		$user   =& JFactory::getUser();
		$userid = $user->get('id');

		$result = false;
		if(is_a($row, 'JTable')) {
			$result = $row->isCheckedOut($userid);
		} else {
			$result = JTable::isCheckedOut($userid, $row->checked_out);
		}

		$checked = '';
		if ( $result ) {
			$checked = KViewHelperGrid::_checkedOut( $row );
		} else {
			$checked = KViewHelper::_('grid.id', $i, $row->$identifier );
		}

		return $checked;
	}

	public static function published( &$row, $i, $imgY = 'tick.png', $imgX = 'publish_x.png', $prefix='' )
	{
		$img 	= $row->published ? $imgY : $imgX;
		$task 	= $row->published ? 'unpublish' : 'publish';
		$alt 	= $row->published ? JText::_( 'Published' ) : JText::_( 'Unpublished' );
		$action = $row->published ? JText::_( 'Unpublish Item' ) : JText::_( 'Publish item' );

		$href = '
		<a href="javascript:void(0);" onclick="return listItemTask(\'cb'. $i .'\',\''. $prefix.$task .'\')" title="'. $action .'">
		<img src="images/'. $img .'" border="0" alt="'. $alt .'" /></a>'
		;

		return $href;
	}
	
	public static function enable( $enable, $i, $imgY = 'tick.png', $imgX = 'publish_x.png', $prefix = '' )
	{
		$img 	= $enable ? $imgY : $imgX;
		$task 	= $enable ? 'disable' : 'enable';
		$alt 	= $enable ? JText::_( 'Enabled' ) : JText::_( 'Disabled' );
		$action = $enable ? JText::_( 'Disable Item' ) : JText::_( 'Enable Item' );

		$href = '
		<a href="javascript:void(0);" onclick="return listItemTask(\'cb'. $i .'\',\''. $prefix.$task .'\')" title="'. $action .'">
		<img src="images/'. $img .'" border="0" alt="'. $alt .'" />
		</a>'
		;

		return $href;
	}

	public static function state( $filter_state='*', $published='Published', $unpublished='Unpublished', $archived=NULL, $trashed=NULL )
	{
		$state[] = KViewHelper::_('select.option',  '', '- '. JText::_( 'Select State' ) .' -' );
		//Jinx : Why is this used ?
		//$state[] = KViewHelper::_('select.option',  '*', JText::_( 'Any' ) );
		$state[] = KViewHelper::_('select.option',  'P', JText::_( $published ) );
		$state[] = KViewHelper::_('select.option',  'U', JText::_( $unpublished ) );

		if ($archived) {
			$state[] = KViewHelper::_('select.option',  'A', JText::_( $archived ) );
		}

		if ($trashed) {
			$state[] = KViewHelper::_('select.option',  'T', JText::_( $trashed ) );
		}

		return KViewHelper::_('select.genericlist',   $state, 'filter_state', 'class="inputbox" size="1" onchange="submitform( );"', 'value', 'text', $filter_state );
	}

	public static function order( $rows, $image='filesave.png', $task="saveorder" )
	{
		$image = KViewHelper::_('image.administrator',  $image, '/images/', NULL, NULL, JText::_( 'Save Order' ) );
		$href = '<a href="javascript:saveorder('.(count( $rows )-1).', \''.$task.'\')" title="'.JText::_( 'Save Order' ).'">'.$image.'</a>';
		return $href;
	}


	protected static function _checkedOut( &$row, $overlib = 1 )
	{
		$hover = '';
		if ( $overlib )
		{
			$text = addslashes(htmlspecialchars($row->editor));

			$date 	= KViewHelper::_('date',  $row->checked_out_time, '%A, %d %B %Y' );
			$time	= KViewHelper::_('date',  $row->checked_out_time, '%H:%M' );

			$hover = '<span class="editlinktip hasTip" title="'. JText::_( 'Checked Out' ) .'::'. $text .'<br />'. $date .'<br />'. $time .'">';
		}
		$checked = $hover .'<img src="images/checked_out.png"/></span>';

		return $checked;
	}
}
