<?php
/**
 * @version		$Id$
 * @category	Koowa
 * @package		Koowa_Template
 * @subpackage	Helper
 * @copyright	Copyright (C) 2007 - 2009 Johan Janssens and Mathias Verraes. All rights reserved.
 * @license		GNU GPL <http://www.gnu.org/licenses/gpl.html>
 * @link     	http://www.koowa.org
 */

/**
 * Template Grid Helper
 *
 * @author		Mathias Verraes <mathias@koowa.org>
 * @category	Koowa
 * @package		Koowa_Template
 * @subpackage	Helper
 */
class KTemplateHelperGrid extends KObject
{
	/**
	 * Shows a true/false graphics
	 *
	 * @param	bool	Value
	 * @param 	string	Image for true
	 * @param 	string	Image for false
	 * @param 	string 	Text for true
	 * @param 	string	Text for false
	 * @return 	string	Html img
	 */
	public function boolean( $bool, $true_img = null, $false_img = null, $true_text = null, $false_text = null)
	{
		$true_img 	= $true_img 	? $true_img 	: 'tick.png';
		$false_img 	= $false_img	? $false_img	: 'publish_x.png';
		$true_text 	= $true_text 	? $true_text 	: 'Yes';
		$false_text = $false_text 	? $false_text 	: 'No';

		return '<img src="images/'. ($bool ? $true_img : $false_img) .'" border="0" alt="'. JText::_($bool ? $true_text : $false_text) .'" />';
	}

	/**
	 * @param	string	The link title
	 * @param	string	The order field for the column
	 * @param	string	The current direction
	 * @param	string	The selected ordering
	 */
	public function sort( $title, $order, $direction = 'asc', $selected = 0)
	{
		//Load koowa javascript
		KTemplate::loadHelper('script', Koowa::getURL('js').'koowa.js');

		$direction	= strtolower( $direction );
		$images		= array( 'sort_asc.png', 'sort_desc.png' );
		$index		= intval( $direction == 'desc' );
		$direction	= ($direction == 'desc') ? 'asc' : 'desc';

		$html = '<a href="javascript:Koowa.Table.sorting(\''.$order.'\',\''.$direction.'\');" title="'.JText::_( 'Click to sort this column' ).'">';
		$html .= JText::_( $title );
		if ($order == $selected ) {
			$html .= KTemplate::loadHelper('image.template',  $images[$index], 'images/', NULL, NULL);
		}
		$html .= '</a>';
		return $html;
	}

	public function publish( $publish, $id, $imgY = 'tick.png', $imgX = 'publish_x.png' )
	{
		//Load koowa javascript
		KTemplate::loadHelper('script', Koowa::getURL('js').'koowa.js');

		$img 	= $publish ? $imgY : $imgX;
		$alt 	= $publish ? JText::_( 'Published' ) : JText::_( 'Unpublished' );
		$text 	= $publish ? JText::_( 'Unpublish Item' ) : JText::_( 'Publish item' );
		$action = $publish ? 'disable' : 'enable';

		$href = '
		<a href="javascript:Koowa.Grid.action(\''.$action.'\', \'cb'. $i .'\')" title="'. $text .'">
		<img src="images/'. $img .'" border="0" alt="'. $alt .'" />
		</a>'
		;

		return $href;
	}

	public function enable( $enable, $id, $imgY = 'tick.png', $imgX = 'publish_x.png')
	{
		//Load koowa javascript
		KTemplate::loadHelper('script', Koowa::getURL('js').'koowa.js');

		$img 	= $enable ? $imgY : $imgX;
		$alt 	= $enable ? JText::_( 'Enabled' ) : JText::_( 'Disabled' );
		$text 	= $enable ? JText::_( 'Disable Item' ) : JText::_( 'Enable Item' );
		$action = $enable ? 'disable' : 'enable';

		$href = '
		<a href="javascript:Koowa.Grid.action(\''.$action.'\', \'cb'. $id .'\')" title="'. $text .'">
		<img src="images/'. $img .'" border="0" alt="'. $alt .'" />
		</a>'
		;

		return $href;
	}

	public function order($id)
	{
		//Load koowa javascript
		KTemplate::loadHelper('script', Koowa::getURL('js').'koowa.js');

		$up   = Koowa::getURL('images').'/arrow_up.png';
		$down = Koowa::getURL('images').'/arrow_down.png';

		$result =
			 '<a href="javascript:Koowa.Grid.order('.$id.', -1)" >'
			.'<img src="'.$up.'" border="0" alt="'.JText::_('Move up').'" />'
			.'</a>'
			.'<a href="javascript:Koowa.Grid.order('.$id.', 1)" >'
			.'<img src="'.$down.'" border="0" alt="'.JText::_('Move down').'" />'
			.'</a>';

		return $result;
	}

	public function access( $access, $id )
	{
		//Load koowa javascript
		KTemplate::loadHelper('script', Koowa::getURL('js').'koowa.js');

		switch($access)
		{
			case 0 :
			{
				$color   = 'style="color: green;"';
				$group   = JText::_('Public');
				$access  = 2;
			} break;

			case 1 :
			{
				$color   = 'style="color: red;"';
				$group   = JText::_('Registered');
				$access  = 3;
			} break;

			case 2 :
			{
				$color   = 'style="color: black;"';
				$group   = JText::_('Special');
				$access  = 1;
			} break;

		}

		$href = '
			<a href="javascript:Koowa.Grid.access(\''.$action.'\', \'cb'. $i .'\',  \''. $access .'\')" '. $color .'>
			'. $group .'</a>'
			;

		return $href;
	}


	/**
	* @param int The row index
	* @param int The record id
	* @param boolean
	* @param string The name of the form element
	* @return string
	*/
	public function id( $rowNum, $recId, $checkedOut = false, $name = 'id' )
	{
		if ( $checkedOut ) {
			return '';
		}

		return '<input type="checkbox" id="cb'.$rowNum.'" name="'.$name.'[]" value="'.$recId.'" onclick="isChecked(this.checked);" />';
	}

	public function checkedOut( $row, $i, $identifier = 'id' )
	{
		$user   = KFactory::get('lib.joomla.user');
		$userid = $user->get('id');

		$result = false;
		if(is_a($row, 'JTable')) {
			$result = $row->isCheckedOut($userid);
		} else {
			$result = JTable::isCheckedOut($userid, $row->checked_out);
		}

		$checked = '';
		if ( $result ) {
			$checked = self::_checkedOut( $row );
		} else {
			$checked = KTemplateDefault::loadHelper('grid.id', $i, $row->$identifier );
		}

		return $checked;
	}

	protected function _checkedOut( $row, $overlib = 1 )
	{
		$hover = '';
		if ( $overlib )
		{
			$text = addslashes(htmlspecialchars($row->editor));

			$date 	= KTemplate::loadHelper('date',  $row->checked_out_time, '%A, %d %B %Y' );
			$time	= KTemplate::loadHelper('date',  $row->checked_out_time, '%H:%M' );

			$hover = '<span class="editlinktip hasTip" title="'. JText::_( 'Checked Out' ) .'::'. $text .'<br />'. $date .'<br />'. $time .'">';
		}
		$checked = $hover .'<img src="images/checked_out.png"/></span>';

		return $checked;
	}
}
