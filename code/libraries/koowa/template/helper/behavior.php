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
 * Template Behavior Helper
 *
 * @author		Johan Janssens <johan@nooku.org>
 * @category	Koowa
 * @package		Koowa_Template
 * @subpackage	Helper
 */
class KTemplateHelperBehavior extends KTemplateHelperAbstract
{
	/*
	 * Array which holds a list of loaded javascript libraries
	 * 
	 * boolean
	 */
	protected $_loaded = array();
	
	/**
	 * Constructor.
	 *
	 * @param 	object 	An optional KConfig object with configuration options
	 */
	public function __construct( KConfig $config = null) 
	{ 
		parent::__construct($config);
		
		//Reset the array of loaded scripts
		$this->_loaded = array();
	}
	
	/**
	 * Method to load the mootools framework into the document head
	 *
	 * - If debugging mode is on an uncompressed version of mootools is included for easier debugging.
	 *
	 * @param	boolean	$debug	Is debugging mode on? [optional]
	 */
	public function mootools($config = array())
	{
		$config = new KConfig($config);
		$config->append(array(
			'debug' => KDEBUG
		));
		
		$html ='';
		
		// Only load once
		if (!isset($this->_loaded['mootools'])) 
		{
			// If no debugging value is set, use the configuration setting
			if($config->debug) {
				$html .= '<script src="media://lib_koowa/js/mootools-uncompressed.js" />';
			} else {
				$html .= '<script src="media://lib_koowa/js/mootools.js" />';
			}
		
			$this->_loaded['mootools'] = true;
		}

		return $html;
	}
	
	/**
	 * Render a modal box
	 *
	 * @return string	The html output
	 */
	public function modal($config = array())
	{
		$config = new KConfig($config);
		$config->append(array(
			'selector' => 'a.modal',
			'options'  => array('disableFx' => true)
 		));
 		
 		$html = '';

		// Load the necessary files if they haven't yet been loaded
		if (!isset($this->_loaded['modal'])) 
		{
			$html .= '<script src="media://system/js/modal.js" />';
			$html .= '<style src="media://system/css/modal.css" />';
			
			$this->_loaded['modal'] = true;
		}
	
		$signature = md5(serialize(array($config->selector,$config->options)));
		if (!isset($this->_loaded[$signature])) 
		{
			$options = !empty($config->options) ? $config->options->toArray() : array(); 
			$html .= "
			<script>
				window.addEvent('domready', function() {
				
				SqueezeBox.initialize(".json_encode($options).");

				$$('".$config->selector."').each(function(el) {
					el.addEvent('click', function(e) {
						new Event(e).stop();
						SqueezeBox.fromElement(el);
					});
				});
			});
			</script>";

			$this->_loaded[$signature] = true;
		}
		
		return $html;
	}

	/**
	 * Render a tooltip
	 * 
	 * @return string	The html output
	 */
	public function tooltip($config = array())
	{
		$config = new KConfig($config);
		$config->append(array(
			'selector' => '.hasTip',
			'options'  => array()
 		));
 		
 		$html = '';
 	
		$signature = md5(serialize(array($config->selector,$config->options)));
		if (!isset($this->_loaded[$signature])) 
		{
			$options = !empty($config->options) ? $config->options->toArray() : array();	
			$html .= "
			<script>
				window.addEvent('domready', function(){ var JTooltips = new Tips($$('".$config->selector."'), '.json_encode($options).'); });
			</script>";
			
			$this->_loaded[$signature] = true;
		}
		
		return $html;
	}

	/**
	 * Render an overlay
	 *
	 * @return string	The html output
	 */
	public function overlay($config = array())
	{
		$config = new KConfig($config);
		$config->append(array(
			'uri'  		=> '',
			'options'  	=> array(),
			'attribs'	=> array()
		));
		
		$html = '';
		
		// Load the necessary files if they haven't yet been loaded
		if (!isset($this->_loaded['overlay'])) 
		{
			$html .= '<script src="media://lib_koowa/js/koowa.js" />';
			$html .= '<style src="media://lib_koowa/css/koowa.css" />';
			
			$options = !empty($config->options) ? $config->options->toArray() : array();
			$html .= "
			<script>
				window.addEvent('domready', function(){ $$('.-koowa-overlay').each(function(overlay){ new Koowa.Overlay(overlay, '".json_encode($options)."'); }); });
			</script>";
			
			$this->_loaded['overlay'] = true;
		}

		$uri = KFactory::tmp('lib.koowa.http.uri', array('uri' => $config->uri));
		$uri->query['tmpl'] = '';
		
		$attribs = KHelperArray::toString($config->attribs);

		$html .= '<div href="'.$uri.'" class="-koowa-overlay" id="'.$uri->fragment.'" '.$attribs.'><div class="-koowa-overlay-status">'.JText::_('Loading...').'</div></div>';
		return $html;
	}
	
	/**
	 * Keep session alive 
	 * 
	 * This will send an ascynchronous request to the server via AJAX on an interval
	 * in miliseconds
	 *
	 * @return string	The html output
	 */
	public function keepalive($config = array())
	{
	    $config = new KConfig($config);
		$config->append(array(
			'refresh'  => 15 * 60000, //15min
		    'url'	   => KRequest::url()
		));
		
		$refresh = (int) $config->refresh;
	
	    // Longest refresh period is one hour to prevent integer overflow.
		if ($refresh > 3600000 || $refresh <= 0) {
			$refresh = 3600000;
		}

		// Build the keepalive script.
		$html = 
		"<script>
			Koowa.keepalive =  function() { 
				var request = new Request({method: 'get', url: '".$config->url."'}).send();
			}
			
			window.addEvent('domready', function() { Koowa.keepalive.periodical('".$refresh."'); });
		</script>";

		return $html;
	}
}