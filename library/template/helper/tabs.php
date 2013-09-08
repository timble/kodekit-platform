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
 * Tabs Template Helper
 *
 * @author  Johan Janssens <http://nooku.assembla.com/profile/johanjanssens>
 * @package Nooku\Library\Template
 */
class TemplateHelperTabs extends TemplateHelperBehavior
{
    /**
     * Creates a pane and creates the javascript object for it
     *
     * @param 	array 	$config An optional array with configuration options
     * @return  string  Html
     */
    public function startPane( $config = array() )
    {
        $config = new ObjectConfigJson($config);
        $config->append(array(
            'id'      => 'pane',
            'attribs' => array(),
            'options' => array()
        ));

        $html  = '';

        // Load the necessary files if they haven't yet been loaded
        if (!isset(self::$_loaded['tabs']))
        {
            $html .= '<script src="assets://js/tabs.js" />';
            self::$_loaded['tabs'] = true;
        }

        $id      = strtolower($config->id);
        $attribs = $this->buildAttributes($config->attribs);
        //Don't pass an empty array as options
        $options = $config->options->toArray() ? ', '.$config->options : '';

        $html .= "
            <script>
                window.addEvent('domready', function(){ new Koowa.Tabs('tabs-".$id."'".$options."); });
            </script>";

        $html .= '<dl class="tabs" id="tabs-'.$id.'" '.$attribs.'>';
        return $html;
    }

    /**
     * Ends the pane
     *
     * @param 	array 	$config An optional array with configuration options
     * @return  string  Html
     */
    public function endPane($config = array())
    {
        return '</dl>';
    }

    /**
     * Creates a tab panel with title and starts that panel
     *
     * @param 	array 	$config An optional array with configuration options
     * @return  string  Html
     */
    public function startPanel( $config = array())
    {
        $config = new ObjectConfigJson($config);
        $config->append(array(
            'title'     => '',
            'attribs'   => array(),
            'options'   => array(),
            'translate' => true
        ));

        $title   = $config->translate ? $this->translate($config->title) : $config->title;
        $attribs = $this->buildAttributes($config->attribs);

        return '<dt '.$attribs.'><span>'.$title.'</span></dt><dd>';
    }

    /**
     * Ends a tab page
     *
     * @param 	array 	$config An optional array with configuration options
     * @return  string  Html
     */
    public function endPanel($config = array())
    {
        return '</dd>';
    }
}