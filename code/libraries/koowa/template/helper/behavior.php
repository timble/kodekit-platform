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
	/**
	 * Array which holds a list of loaded javascript libraries
	 *
	 * boolean
	 */
	protected static $_loaded = array();

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
		$html ='';

		// Only load once
		if (!isset(self::$_loaded['mootools'])) 
		{
			$html .= '<script src="media://lib_koowa/js/mootools.js" />';
			self::$_loaded['mootools'] = true;
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
		if (!isset(self::$_loaded['modal']))
		{
			$html .= '<script src="media://lib_koowa/js/modal.js" />';
			$html .= '<style src="media://lib_koowa/css/modal.css" />';

			self::$_loaded['modal'] = true;
		}

		$signature = md5(serialize(array($config->selector,$config->options)));
		if (!isset(self::$_loaded[$signature]))
		{
			$options = !empty($config->options) ? $config->options->toArray() : array();
			$html .= "
			<script>
				window.addEvent('domready', function() {

				SqueezeBox.initialize(".json_encode($options).");
				SqueezeBox.assign($$('".$config->selector."'), {
			        parse: 'rel'
				});
			});
			</script>";

			self::$_loaded[$signature] = true;
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
		if (!isset(self::$_loaded[$signature]))
		{
		    //Don't pass an empty array as options
			$options = $config->options->toArray() ? ', '.$config->options : '';
			$html .= "
			<script>
				window.addEvent('domready', function(){ new Tips($$('".$config->selector."')".$options."); });
			</script>";

			self::$_loaded[$signature] = true;
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
			'url'  		=> '',
			'options'  	=> array(),
			'attribs'	=> array()
		));

		$html = '';
		// Load the necessary files if they haven't yet been loaded
		if (!isset(self::$_loaded['overlay']))
		{
			$html .= '<script src="media://lib_koowa/js/koowa.js" />';
			$html .= '<style src="media://lib_koowa/css/koowa.css" />';

			//Don't pass an empty array as options
			$options = $config->options->toArray() ? ', '.$config->options : '';
			$html .= "
			<script>
				window.addEvent('domready', function(){ $$('.-koowa-overlay').each(function(overlay){ new Koowa.Overlay(overlay".$options."); }); });
			</script>";

			self::$_loaded['overlay'] = true;
		}

		$url = KFactory::get('koowa:http.url', array('url' => $config->url));
		$url->query['tmpl'] = '';

		$attribs = KHelperArray::toString($config->attribs);

		$html .= '<div href="'.$url.'" class="-koowa-overlay" id="'.$url->fragment.'" '.$attribs.'><div class="-koowa-overlay-status">'.JText::_('Loading...').'</div></div>';
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
	
	/**
	 * Loads the Forms.Validator class and connects it to Koowa.Controller
	 *
	 * This allows you to do easy, css class based forms validation-
	 * Koowa.Controller.Form works with it automatically.
	 * Requires koowa.js and mootools to be loaded in order to work.
	 *
	 * @see    http://www.mootools.net/docs/more125/more/Forms/Form.Validator
	 *
	 * @return string	The html output
	 */
	public function validator($config = array())
	{
	    $config = new KConfig($config);
		$config->append(array(
			'selector' => '.-koowa-form',
		    'options'  => array(
		        'scrollToErrorsOnChange' => true,
		        'scrollToErrorsOnBlur'   => true
		    )
		));

		$html = '';
		// Load the necessary files if they haven't yet been loaded
		if(!isset(self::$_loaded['validator']))
		{
		    if(version_compare(JVERSION,'1.6.0','ge')) {
		        $html .= '<script src="media://lib_koowa/js/validator-1.3.js" />';
		    } else {
		        $html .= '<script src="media://lib_koowa/js/validator-1.2.js" />';
		    }

            self::$_loaded['validator'] = true;
        }

		//Don't pass an empty array as options
		$options = $config->options->toArray() ? ', '.$config->options : '';
		$html .= "<script>
		window.addEvent('domready', function(){
		    $$('$config->selector').each(function(form){
		        new Form.Validator.Inline(form".$options.");
		        form.addEvent('validate', form.validate.bind(form));
		    });
		});
		</script>";

		return $html;
	}
	
	/**
	 * Render a input field that has autocomplete functionality
	 *
	 * @see    http://mootools.net/forge/p/meio_autocomplete
	 * @return string	The html output
	 */
	public function autocomplete($config = array())
	{
		$config = new KConfig($config);
		
		$config->append(array(
			'model'	     => null,
			'validate'   => true
		));
		
		if(!is_a($config->model, 'KIdentifierInterface'))
		{
		    $config->model = KIdentifier::identify($config->model);
		}
		
		$config->append(array(
		    'url'     => JRoute::_('&option=com_'.$config->model->package.'&view='.$config->model->name.'&format=json', true),
		    'column'  => KInflector::singularize($config->model->name).'_id'
		))->append(array(
		    'value'   => $config->{$config->column} ? $config->{$config->column} : '',
		    'attribs' => array(
		        'name'  => $config->column,
		        'type'  => 'text',
		        'class' => 'inputbox value',
		        'size'  => 60
		    )
		))->append(array(
		    'options' => array(
		        'valueField' => $config->attribs->name.'-value',
		        'filter'     => array(
		            'type' => 'contains',
		            'path' => 'name'			        
		        ),
		        'urlOptions' => array(
		            'queryVarname' => 'search'
		        ),
		        'requestOptions' => array(
		            'method' => 'get'
		        )
		        
		    )
		))->append(array(
		    'attribs' => array(
		        'id' => $config->attribs->name,
		        'data-value' => $config->options->valueField,
		    )
		));
		
		if($config->validate) {
		    $config->attribs->class = $config->attribs->class.' ma-required';
		}
		
		$html = '';
		
		// Load the necessary files if they haven't yet been loaded
		if(!isset(self::$_loaded['autocomplete']))
		{
		    $html .= '<script src="media://lib_koowa/js/autocomplete.js" />';
		    $html .= '<script src="media://lib_koowa/js/patch.autocomplete.js" />';
		    $html .= '<style src="media://lib_koowa/css/autocomplete.css" />';
		}
		
		$html .= "
		<script>
			window.addEvent('domready', function(){				
				new Meio.Autocomplete.Select($('".$config->attribs->id."'), ".json_encode($config->url).", ".$config->options.");
			});
		</script>";
		$html .= '<input '.KHelperArray::toString($config->attribs).' />';
	    $html .= '<input '.KHelperArray::toString(array(
            'type'  => 'hidden',
            'name'  => $config->attribs->name,
            'id'    => $config->options->valueField,
            'value' => $config->value
	       )).' />';

	    return $html;
	}
}