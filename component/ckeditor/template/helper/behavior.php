<?php
/**
 * Kodekit Component - http://www.timble.net/kodekit
 *
 * @copyright	Copyright (C) 2011 - 2016 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license		MPL v2.0 <https://www.mozilla.org/en-US/MPL/2.0>
 * @link		https://github.com/timble/kodekit-ckeditor for the canonical source repository
 */

namespace Kodekit\Component\Ckeditor;

use Kodekit\Library;

/**
 * Behavior Template Helper
 *
 * @author  Johan Janssens <http://github.com/johanjanssens>
 * @package Kodekit\Component\Ckeditor
 */
class TemplateHelperBehavior extends Library\TemplateHelperBehavior
{
    /**
     * Loads the inline editor behavior and attaches it to a specified element
     *
     * @param 	array 	$config An optional array with configuration options
     * @return string    The html output
     *
     */
    public function editor($config = array())
    {
        $config = new Library\ObjectConfigJson($config);
        $config->append(array(
            'url' => '',
            'options' => array(),
            'attribs' => array(),
        ));

        $html = '';
        // Load the necessary files if they haven't yet been loaded
        if (!isset(self::$_loaded['editor']))
        {
            $html .= '<ktml:script src="assets://ckeditor/ckeditor/ckeditor.js" />';
            self::$_loaded['editor'] = true;
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
                            data['csrf_token'] = '".$this->getObject('user')->getSession()->getToken()."';

                            jQuery.post('".$url."', data);
                        });
                    });
            });</script>";


        return $html;
    }
}