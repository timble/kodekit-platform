<?php
/**
 * @version		$Id: behavior.php 1051 2009-07-13 22:08:57Z Johan $
 * @category	Koowa
 * @package		Koowa_Template
 * @subpackage	Helper
 * @copyright	Copyright (C) 2007 - 2009 Johan Janssens and Mathias Verraes. All rights reserved.
 * @license		GNU GPL <http://www.gnu.org/licenses/gpl.html>
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
	public function __construct( array $params = array() )
	{
		//Load koowa javascript
		KTemplate::loadHelper('behavior.mootools');
		KTemplate::loadHelper('script', Koowa::getURL('js').'tabs.js');
	}

	/**
	 * Creates a pane and creates the javascript object for it
	 *
	 * @param	array	An associative array of pane attributes
	 */
	public function startPane( $id, array $attribs = array() )
	{
		$id = strtolower($id);
	
		$document = KFactory::get('lib.joomla.document');

		$options = '{';
		$opt['onActive']	 = (isset($params['onActive'])) ? $params['onActive'] : null ;
		$opt['onBackground'] = (isset($params['onBackground'])) ? $params['onBackground'] : null ;
		$opt['display']		 = (isset($params['startOffset'])) ? (int)$params['startOffset'] : null ;
		
		foreach ($opt as $k => $v)
		{
			if ($v) {
				$options .= $k.': '.$v.',';
			}
		}
		
		if (substr($options, -1) == ',') {
			$options = substr($options, 0, -1);
		}
		$options .= '}';

		$js = '		window.addEvent(\'domready\', function(){ $$(\'dl#tabs-'.$id.'\').each(function(tabs){ new KTabs(tabs, '.$options.'); }); });';

		$document->addScriptDeclaration( $js );	
	
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