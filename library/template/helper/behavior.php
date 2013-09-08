<?php
/**
 * Nooku Framework - http://www.nooku.org
 *
 * @copyright	Copyright (C) 2007 - 2013 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		git://git.assembla.com/nooku-framework.git for the canonical source repository
 */

namespace Nooku\Library;

/**
 * Behavior Template Helper
 *
 * @author  Johan Janssens <http://nooku.assembla.com/profile/johanjanssens>
 * @package Nooku\Library\Template
 */
class TemplateHelperBehavior extends TemplateHelperAbstract
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
     * @param array $config An optional array with configuration options
     * @return string    The html output
     */
    public function mootools($config = array())
    {
        $html = '';

        // Only load once
        if (!isset(self::$_loaded['mootools']))
        {
            $config = new ObjectConfig($config);

            $html .= '<script src="assets://js/mootools.js" />';
            self::$_loaded['mootools'] = true;
        }

        return $html;
    }

    /**
     * Render a modal box
     *
     * @param 	array 	$config An optional array with configuration options
     * @return string    The html output
     */
    public function modal($config = array())
    {
        $config = new ObjectConfigJson($config);
        $config->append(array(
            'selector' => 'a.modal',
            'options'  => array('disableFx' => true)
        ));

        $html = '';

        // Load the necessary files if they haven't yet been loaded
        if (!isset(self::$_loaded['modal'])) {
            $html .= '<script src="assets://js/modal.js" />';
            $html .= '<style src="assets://css/modal.css" />';

            self::$_loaded['modal'] = true;
        }

        $signature = 'modal-' . $config->selector;
        if (!isset(self::$_loaded[$signature]))
        {
            $options = !empty($config->options) ? $config->options->toArray() : array();
            $html .= "
			<script>
				window.addEvent('domready', function() {

				SqueezeBox.initialize(" . json_encode($options) . ");
				SqueezeBox.assign($$('" . $config->selector . "'), {
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
     * @param 	array 	$config An optional array with configuration options
     * @return string    The html output
     */
    public function tooltip($config = array())
    {
        $config = new ObjectConfigJson($config);
        $config->append(array(
            'selector' => '.hasTip',
            'options' => array()
        ));

        $html = '';

        $signature = 'tooltip-' . $config->selector;
        if (!isset(self::$_loaded[$signature]))
        {
            //Don't pass an empty array as options
            $options = $config->options->toArray() ? ', ' . $config->options : '';
            $html .= "
			<script>
				window.addEvent('domready', function(){ new Tips($$('" . $config->selector . "')" . $options . "); });
			</script>";

            self::$_loaded[$signature] = true;
        }

        return $html;
    }

    /**
     * Render an overlay
     *
     * @param 	array 	$config An optional array with configuration options
     * @return string    The html output
     */
    public function overlay($config = array())
    {
        $config = new ObjectConfigJson($config);
        $config->append(array(
            'url' => '',
            'options' => array(),
            'attribs' => array(),
        ));

        $html = '';
        // Load the necessary files if they haven't yet been loaded
        if (!isset(self::$_loaded['overlay']))
        {
            $html .= '<script src="assets://js/koowa.js" />';
            $html .= '<style src="assets://css/koowa.css" />';

            self::$_loaded['overlay'] = true;
        }

        $url = $this->getObject('lib:http.url', array('url' => $config->url));

        //Force tmpl to overlay
        $url->query['tmpl'] = 'overlay';

        $attribs = $this->buildAttributes($config->attribs);

        $id = 'overlay' . rand();
        if ($url->fragment) {
            //Allows multiple identical ids, legacy should be considered replaced with #$url->fragment instead
            $config->append(array(
                'options' => array(
                    'selector' => '[id=' . $url->fragment . ']'
                )
            ));
        }

        //Don't pass an empty array as options
        $options = $config->options->toArray() ? ', ' . $config->options : '';
        $html .= "<script>window.addEvent('domready', function(){new Koowa.Overlay('$id'" . $options . ");});</script>";

        $html .= '<div data-url="' . $url . '" class="-koowa-overlay" id="' . $id . '" ' . $attribs . '><div class="-koowa-overlay-status">' . $this->translate('Loading...') . '</div></div>';
        return $html;
    }

    /**
     * Keep session alive
     *
     * This will send an ascynchronous request to the server via AJAX on an interval in miliseconds
     *
     * @param 	array 	$config An optional array with configuration options
     * @return string    The html output
     */
    public function keepalive($config = array())
    {
        $config = new ObjectConfigJson($config);
        $config->append(array(
            'refresh' => 15 * 60000, //default refresh is 15min
            'url'     => $this->getTemplate()->getView()->getRoute('', false, false),
        ));

        $html = '';

        // Only load once
        if (!isset(self::$_loaded['keepalive']))
        {
            $session = $this->getObject('user')->getSession();
            if($session->isActive())
            {
                //Get the config session lifetime
                $lifetime = $session->getLifetime() * 1000;

                //Refresh time is 1 minute less than the lifetime
                $refresh =  ($lifetime <= 60000) ? 30000 : $lifetime - 60000;
            }
            else $refresh = (int) $config->refresh;

            // Longest refresh period is one hour to prevent integer overflow.
            if ($refresh > 3600000 || $refresh <= 0) {
                $refresh = 3600000;
            }

            // Build the keep alive script.
            $html =
                "<script>
				Koowa.keepalive =  function() {
					var request = new Request({method: 'get', url: '" . $config->url . "'}).send();
				}

				window.addEvent('domready', function() { Koowa.keepalive.periodical('" . $refresh . "'); });
			</script>";

            self::$_loaded['keepalive'] = true;
        }

        return $html;
    }

    /**
     * Loads the Forms.Validator class and connects it to Koowa.Controller
     *
     * This allows you to do easy, css class based forms validation Koowa.Controller.Form works with it automatically.
     * Requires koowa.js and mootools to be loaded in order to work.
     *
     * @see    http://www.mootools.net/docs/more125/more/Forms/Form.Validator
     *
     * @param 	array 	$config An optional array with configuration options
     * @return string    The html output
     */
    public function validator($config = array())
    {
        $config = new ObjectConfigJson($config);
        $config->append(array(
            'selector' => '.-koowa-form',
            'options'  => array(
                'scrollToErrorsOnChange' => true,
                'scrollToErrorsOnBlur'   => true
            )
        ));

        $html = '';
        // Load the necessary files if they haven't yet been loaded
        if (!isset(self::$_loaded['validator'])) {
            $html .= '<script src="assets://js/validator-1.2.js" />';
            $html .= '<script src="assets://js/patch.validator.js" />';

            self::$_loaded['validator'] = true;
        }

        $signature = 'validator-' . $config->selector;
        if (!isset(self::$_loaded[$signature]))
        {
            //Don't pass an empty array as options
            $options = $config->options->toArray() ? ', ' . $config->options : '';
            $html .= "<script>
			window.addEvent('domready', function(){
		    	$$('$config->selector').each(function(form){
		        	new Koowa.Validator(form" . $options . ");
		        	form.addEvent('validate', form.validate.bind(form));
		   	 });
			});
			</script>";

            self::$_loaded[$signature] = true;
        }

        return $html;
    }

    /**
     * Loads the autocomplete behavior and attaches it to a specified element
     *
     * @see http://mootools.net/forge/p/meio_autocomplete
     *
     * @param 	array 	$config An optional array with configuration options
     * @return string    The html output
     *
     */
    public function autocomplete($config = array())
    {
        $config = new ObjectConfigJson($config);
        $config->append(array(
            'identifier'    => null,
            'element'       => null,
            'path'          => 'name',
            'filter'        => array(),
            'validate'      => true,
            'selected'      => null,
            'name'          => $config->value
        ))->append(array(
            'value_element' => $config->element . '-value',
            'attribs' => array(
                'id'    => $config->element,
                'type'  => 'text',
                'class' => 'inputbox value',
                'size'  => 60
            ),
        ))->append(array(
            'options' => array(
                'valueField' => $config->value_element,
                'filter'     => array('path' => $config->path),
                'requestOptions' => array('method' => 'get'),
                'urlOptions'    => array(
                    'queryVarName' => 'search',
                    'extraParams'  => ObjectConfig::unbox($config->filter)
                )
            )
        ));

        if ($config->validate)
        {
            $config->attribs['data-value'] = $config->element . '-value';
            $config->attribs['class'] .= ' ma-required';
        }

        if (!isset($config->url))
        {
            $identifier = $this->getIdentifier($config->identifier);
            $config->url = $this->getTemplate()->getView()->getRoute(
                'option=com_' . $identifier->package . '&view=' . $identifier->name . '&format=json', false, false
            );
        }

        $html = '';

        // Load the necessary files if they haven't yet been loaded
        if (!isset(self::$_loaded['autocomplete'])) {
            $html .= '<script src="assets://js/autocomplete.js" />';
            $html .= '<script src="assets://js/patch.autocomplete.js" />';
            $html .= '<style src="assets://css/autocomplete.css" />';
        }

        $html .= "
		<script>
			window.addEvent('domready', function(){				
				new Koowa.Autocomplete($('" . $config->element . "'), " . json_encode((string)$config->url) . ", " . $config->options . ");
			});
		</script>";

        $html .= '<input ' . $this->buildAttributes($config->attribs) . ' />';
        $html .= '<input ' . $this->buildAttributes(array(
            'type' => 'hidden',
            'name' => $config->name,
            'id' => $config->element . '-value',
            'value' => $config->selected
        )) . ' />';

        return $html;
    }

    /**
     * Drag and Drop Sortables Behavior
     *
     * @param 	array 	$config An optional array with configuration options
     * @return	string 	Html
     */
    public function sortable($config = array())
    {
        $config = new ObjectConfigJson($config);
        $config->append(array(
            'option'	=> 'com_'.$this->getIdentifier()->getPackage(),
            'view'		=> StringInflector::singularize($this->getTemplate()->getView()->getName()),
            'selector'	=> 'table tbody.sortable',
            'direction' => 'asc',
            'url'       => '?format=json'
        ))->append(array(
                'options'	=> array(
                    'handle'	=> 'td.handle',
                    'numcolumn'	=> '.grid-count',
                    'direction' => $config->direction,
                    'adapter'	=> array(
                        'type'		=> 'koowa',
                        'options'	=> array(
                            'url'		=> $config->url,
                            'data'	=> array(
                                '_token'	=> $this->getObject('user')->getSession()->getToken(),
                                '_action'	=> 'edit'
                            ),
                            'key'		=> 'order',
                            'offset'	=> 'relative'
                        )
                    )
                )
            ));

        $html = '';

        $signature = md5(serialize(array($config->selector,$config->options)));
        if (!isset($this->_loaded[$signature]))
        {
            $options = !empty($config->options) ? $config->options->toArray() : array();
            $html .= "
                <script src=\"/administrator/theme/default/js/sortables.js\" />
                <style src=\"/administrator/theme/default/stylesheets/sortables.css\" />
				<script>
				(function(){
					var sortable = function() {
						$$('".$config->selector."').sortable(".json_encode($options).");
					};
					window.addEvents({domready: sortable, request: sortable});
				})();
				</script>
			";

            $this->_loaded[$signature] = true;
        }

        return $html;
    }

    /**
     * Loads the inline editor behavior and attaches it to a specified element
     *
     * @see http://mootools.net/forge/p/meio_autocomplete
     *
     * @param 	array 	$config An optional array with configuration options
     * @return string    The html output
     *
     */
    public function inline_editing($config = array())
    {
        $config = new ObjectConfigJson($config);
        $config->append(array(
            'url' => '',
            'options' => array(),
            'attribs' => array(),
        ));

        $html = '';
        // Load the necessary files if they haven't yet been loaded
        if (!isset(self::$_loaded['inline_editing']))
        {
            $html .= '<script src="assets://application/js/jquery.js" />';
            $html .= '<script src="assets://ckeditor/ckeditor/ckeditor.js" />';

            self::$_loaded['inline_editing'] = true;
        }

        $url = $this->getObject('lib:http.url', array('url' => $config->url));

        $html .= "<script>window.addEvent('domready', function(){
                    CKEDITOR.on( 'instanceCreated', function( event ) {
                        var editor = event.editor,
                            element = editor.element;

                        if ( element.is( 'h1', 'h2', 'h3' ) || element.getAttribute( 'id' ) == 'taglist' ) {
                            editor.on( 'configLoaded', function() {
                                editor.config.toolbar = 'title';
                            });
                        }else{
                            editor.on( 'configLoaded', function() {
                                editor.config.toolbar = 'standard';
                            });
                        }
                        editor.on('blur', function (ev) {
                            var data = {};

                            // Need to do this because we don't know what field there is being edited....
                            data[editor.element.getId()] = editor.getData();
                            data['_token'] = '".$this->getObject('user')->getSession()->getToken()."';

                            jQuery.post('".$url."', data);
                        });
                    });
            });</script>";


        return $html;
    }
}