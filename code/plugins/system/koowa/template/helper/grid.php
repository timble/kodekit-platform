<?php
/**
 * @version		$Id$
 * @category	Koowa
 * @package		Koowa_Template
 * @subpackage	Helper
 * @copyright	Copyright (C) 2007 - 2010 Johan Janssens and Mathias Verraes. All rights reserved.
 * @license		GNU GPLv2 <http://www.gnu.org/licenses/old-licenses/gpl-2.0.html>
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
		$img = '';

		// cleanup
		$direction	= strtolower($direction);
		$direction 	= in_array($direction, array('asc', 'desc')) ? $direction : 'asc';


		// only for the current sorting
		if($order == $selected)
		{
			$img = KTemplate::loadHelper('image.template',   'sort_'.$direction.'.png', 'images/', NULL, NULL);
			$direction = $direction == 'desc' ? 'asc' : 'desc'; // toggle
		}

		// modify url
		$url = clone KRequest::url();
		$query = $url->getQuery(1);
		$query['order'] 	 = $order;
		$query['direction'] = $direction;
		$url->setQuery($query);

		// render html
		$html  = '<a href="'.JRoute::_($url).'" title="'.JText::_('Click to sort by this column').'">';
		$html .= JText::_($title).$img;
		$html .= '</a>';

		return $html;
	}

	public function publish( $publish, $id, $imgY = 'tick.png', $imgX = 'publish_x.png' )
	{
		//Load koowa javascript
		KTemplate::loadHelper('script', KRequest::root().'/media/plg_koowa/js/koowa.js');

		$img 	= $publish ? $imgY : $imgX;
		$alt 	= $publish ? JText::_( 'Published' ) : JText::_( 'Unpublished' );
		$text 	= $publish ? JText::_( 'Unpublish Item' ) : JText::_( 'Publish item' );
		$action = $publish ? 'disable' : 'enable';

		$href = '
		<a href="javascript:KGrid.action(\''.$action.'\', \'cb'. $i .'\')" title="'. $text .'">
		<img src="images/'. $img .'" border="0" alt="'. $alt .'" />
		</a>'
		;

		return $href;
	}

	public function enable( $enable, $id, $imgY = 'tick.png', $imgX = 'publish_x.png')
	{
		//Load koowa javascript
		KTemplate::loadHelper('script', KRequest::root().'/media/plg_koowa/js/koowa.js');

		$img 	= $enable ? $imgY : $imgX;
		$alt 	= $enable ? JText::_( 'Enabled' ) : JText::_( 'Disabled' );
		$text 	= $enable ? JText::_( 'Disable Item' ) : JText::_( 'Enable Item' );
		$action = $enable ? 'disable' : 'enable';

		$href = '
		<a href="javascript:KGrid.action(\''.$action.'\', \'cb'. $id .'\')" title="'. $text .'">
		<img src="images/'. $img .'" border="0" alt="'. $alt .'" />
		</a>'
		;

		return $href;
	}

	public function order($id)
	{
		//Load koowa javascript
		KTemplate::loadHelper('script', KRequest::root().'/media/plg_koowa/js/koowa.js');

		$up   = KRequest::root().'/media/plg_koowa/images/arrow_up.png';
		$down = KRequest::root().'/media/plg_koowa/images/arrow_down.png';

		$result =
			 '<a href="javascript:KGrid.order('.$id.', -1)" >'
			.'<img src="'.$up.'" border="0" alt="'.JText::_('Move up').'" />'
			.'</a>'
			.'<a href="javascript:KGrid.order('.$id.', 1)" >'
			.'<img src="'.$down.'" border="0" alt="'.JText::_('Move down').'" />'
			.'</a>';

		return $result;
	}

	public function access( $access, $id )
	{
		//Load koowa javascript
		KTemplate::loadHelper('script', KRequest::root().'/media/plg_koowa/js/koowa.js');

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
			<a href="javascript:KGrid.access(\''.$action.'\', \'cb'. $i .'\',  \''. $access .'\')" '. $color .'>
			'. $group .'</a>'
			;

		return $href;
	}

	public function id( $id, $row, $name = 'id' )
	{
		if(isset($row->locked) && $row->locked) 
		{
			$text = $row->locked_by;
			$date = KTemplate::loadHelper('date',  $row->locked_on, '%A, %d %B %Y' );
			$time = KTemplate::loadHelper('date',  $row->locked_on, '%H:%M' );

			$html = '<span class="editlinktip hasTip" title="'. JText::_( 'Locked by ' ) . $text .' on '. $date .' at '. $time .'">
						<img src="images/checked_out.png"/>
					</span>';
		} 
		else $html = '<input type="checkbox" id="cb'.$id.'" name="'.$name.'[]" value="'.$row->$name.'" />';
		
		return $html;
	}
}
