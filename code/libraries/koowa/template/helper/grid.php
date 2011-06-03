<?php
/**
 * @version		$Id$
 * @category	Koowa
 * @package		Koowa_Template
 * @subpackage	Helper
 * @copyright	Copyright (C) 2007 - 2010 Johan Janssens. All rights reserved.
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link     	http://www.nooku.org
 */

/**
 * Template Grid Helper
 *
 * @author		Johan Janssens <johan@nooku.org>
 * @category	Koowa
 * @package		Koowa_Template
 * @subpackage	Helper
 * @see 		http://ajaxpatterns.org/Data_Grid
 */
class KTemplateHelperGrid extends KTemplateHelperAbstract
{
	/**
	 * Render a checkbox field
	 *
	 * @param 	array 	An optional array with configuration options
	 * @return	string	Html
	 */
	public function checkbox($config = array())
	{
		$config = new KConfig($config);
		$config->append(array(
			'row'  		=> null,
		));

		if($config->row->isLockable() && $config->row->locked())
		{
		    $html = '<span class="editlinktip hasTip" title="'.$config->row->lockMessage() .'">
						<img src="media://lib_koowa/images/locked.png"/>
					</span>';
		}
		else
		{
		    $column = $config->row->getIdentityColumn();
		    $value  = $config->row->{$column};

		    $html = '<input type="checkbox" class="-koowa-grid-checkbox" name="'.$column.'[]" value="'.$value.'" />';
		}

		return $html;
	}
	
	/**
	 * Render an search header
	 *
	 * @param 	array 	An optional array with configuration options
	 * @return	string	Html
	 */
	public function search($config = array())
	{
	    $config = new KConfig($config);
		$config->append(array(
			'search' => null,
		));
	    
	    $html = '<input name="search" id="search" value="'.$config->search.'" />';
        $html .= '<button>'.JText::_('Go').'</button>';
		$html .= '<button onclick="document.getElementById(\'search\').value=\'\';this.form.submit();">'.JText::_('Reset').'</button>';
	
	    return $html;
	}

	/**
	 * Render a checkall header
	 *
	 * @param 	array 	An optional array with configuration options
	 * @return	string	Html
	 */
	public function checkall($config = array())
	{
		$config = new KConfig($config);

		$html = '<input type="checkbox" class="-koowa-grid-checkall" />';
		return $html;
	}

	/**
	 * Render a sorting header
	 *
	 * @param 	array 	An optional array with configuration options
	 * @return	string	Html
	 */
	public function sort( $config = array())
	{
		$config = new KConfig($config);
		$config->append(array(
			'title'   	=> '',
			'column'  	=> '',
			'direction' => 'asc',
			'sort'		=> ''
		));


		//Set the title
		if(empty($config->title)) {
			$config->title = ucfirst($config->column);
		}

		//Set the direction
		$direction	= strtolower($config->direction);
		$direction 	= in_array($direction, array('asc', 'desc')) ? $direction : 'asc';

		//Set the class
		$class = '';
		if($config->column == $config->sort)
		{
			$direction = $direction == 'desc' ? 'asc' : 'desc'; // toggle
			$class = 'class="-koowa-'.$direction.'"';
		}

		$url = clone KRequest::url();

		$query 				= $url->getQuery(1);
		$query['sort'] 		= $config->column;
		$query['direction'] = $direction;
		$url->setQuery($query);

		$html  = '<a href="'.JRoute::_('index.php?'.$url->getQuery()).'" title="'.JText::_('Click to sort by this column').'"  '.$class.'>';
		$html .= JText::_($config->title);
		$html .= '</a>';

		return $html;
	}

	/**
	 * Render an enable field
	 *
	 * @param 	array 	An optional array with configuration options
	 * @return	string	Html
	 */
	public function enable($config = array())
	{
		$config = new KConfig($config);
		$config->append(array(
			'row'  		=> null,
		));

		$html = '';
		$html .= '<script src="media://lib_koowa/js/koowa.js" />';

		$img    = $config->row->enabled ? 'enabled.png' : 'disabled.png';
		$alt 	= $config->row->enabled ? JText::_( 'Enabled' ) : JText::_( 'Disabled' );
		$text 	= $config->row->enabled ? JText::_( 'Disable Item' ) : JText::_( 'Enable Item' );
		$value 	= $config->row->enabled ? 0 : 1;

		$data  = "{enabled:$value}";
		$html .= '<img src="media://lib_koowa/images/'. $img .'" border="0" alt="'. $alt .'" data-action="edit" data-data="'.$data.'" title='.$text.' />';

		return $html;
	}

	/**
	 * Render an order field
	 *
	 * @param 	array 	An optional array with configuration options
	 * @return	string	Html
	 */
	public function order($config = array())
	{
		$config = new KConfig($config);
		$config->append(array(
			'row'  		=> null,
		    'total'		=> null
		));

		$html = '';
		$html .= '<script src="media://lib_koowa/js/koowa.js" />';

		$up   = 'media://lib_koowa/images/arrow_up.png';
		$down = 'media://lib_koowa/images/arrow_down.png';

		$updata   = "{order:-1}";
		$downdata = "{order:1}";

		if ($config->row->ordering > 1) {
            $html .= '<img src="'.$up.'" border="0" alt="'.JText::_('Move up').'" data-action="edit" data-data="'.$updata.'" />';
        }

        $html .= $config->row->ordering;

        if($config->row->ordering != $config->total) {
            $html .= '<img src="'.$down.'" border="0" alt="'.JText::_('Move down').'" data-action="edit" data-data="'.$downdata.'"/>';
	    }

		return $html;
	}

	/**
	 * Render an access field
	 *
	 * @param 	array 	An optional array with configuration options
	 * @return	string	Html
	 */
	public function access($config = array())
	{
		$config = new KConfig($config);
		$config->append(array(
			'row'  		=> null,
		));

		$html = '';
		$html .= '<script src="media://lib_koowa/js/koowa.js" />';

		switch($config->row->access)
		{
			case 0 :
			{
				$color   = 'green';
				$group   = JText::_('Public');
				$access  = 1;
			} break;

			case 1 :
			{
				$color   = 'red';
				$group   = JText::_('Registered');
				$access  = 2;
			} break;

			case 2 :
			{
				$color   = 'black';
				$group   = JText::_('Special');
				$access  = 0;
			} break;

		}

		$data  = "{access:$access}";
		$html .= '<span style="color:'.$color.'" data-action="edit" data-data="'.$data.'" />'.$group.'</span>';

		return $html;
	}
}