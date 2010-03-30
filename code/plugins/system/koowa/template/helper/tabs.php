<?php
/**
 * @version		$Id: behavior.php 1051 2009-07-13 22:08:57Z Johan $
 * @category	Koowa
 * @package		Koowa_Template
 * @subpackage	Helper
 * @copyright	Copyright (C) 2007 - 2010 Johan Janssens and Mathias Verraes. All rights reserved.
 * @license		GNU GPLv2 <http://www.gnu.org/licenses/old-licenses/gpl-2.0.html>
 * @link     	http://www.koowa.org
 */

/**
 * Template Tabs Behavior Helper
 *
 * @author		Johan Janssens <johan@koowa.org>
 * @category	Koowa
 * @package		Koowa_Template
 * @subpackage	Helper
 * @uses		KArrayHelper
 */
class KTemplateHelperTabs extends KObject
{
	/**
	 * Constructor
	 *
	 * @param array Associative array of values
	 */
	public function __construct()
	{
		//Load koowa javascript
		KTemplate::loadHelper('behavior.mootools');
		KTemplate::loadHelper('script', KRequest::root().'/media/plg_koowa/js/tabs.js');
	}

	/**
	 * Creates a pane and creates the javascript object for it
	 *
	 * @param	array	An associative array of behavior options
	 * @param	array	An associative array of pane attributes
	 */
	public function startPane( $id, array $options = array(), array $attribs = array() )
	{
		$id = strtolower($id);
	
		$js = 'window.addEvent(\'domready\', function(){ new KTabs(\'tabs-'.$id.'\', \''.json_encode($options).'\'); });';
		$document = KFactory::get('lib.koowa.document')->addScriptDeclaration( $js );	
	
		$attribs = KHelperArray::toString($attribs);	
		return '<dl class="tabs" id="tabs-'.$id.'" '.$attribs.'>';
	}

	/**
	 * Ends the pane
	 */
	public function endPane()
	{
		return "</dl>";
	}

	/**
	 * Creates a tab panel with title and starts that panel
	 *
	 * @param	string	The title of the tab
	 * @param	array	An associative array of pane attributes
	 */
	public function startPanel( $title, array $attribs = array())
	{
		$attribs = KHelperArray::toString($attribs);
		return '<dt '.$attribs.'><span>'.$title.'</span></dt><dd>';
	}

	/**
	 * Ends a tab page
	 */
	public function endPanel()
	{
		return '</dd>';
	}
}