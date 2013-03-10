<?php
/**
 * @package     Koowa_Template
 * @subpackage  Helper
 * @copyright   Copyright (C) 2007 - 2012 Johan Janssens. All rights reserved.
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://www.nooku.org
 */

namespace Nooku\Framework;

/**
 * Template Tabs Behavior Helper
 *
 * @author      Johan Janssens <johan@nooku.org>
 * @package     Koowa_Template
 * @subpackage  Helper
 */
class TemplateHelperTabs extends TemplateHelperBehavior
{
    /**
     * Creates a pane and creates the javascript object for it
     *
     * @param   array   An optional array with configuration options
     * @return  string  Html
     */
    public function startPane( $config = array() )
    {
        $config = new Config($config);
        $config->append(array(
            'id'      => 'pane',
            'attribs' => array(),
            'options' => array()
        ));

        $html  = '';

        // Load the necessary files if they haven't yet been loaded
        if (!isset(self::$_loaded['tabs']))
        {
            $html .= '<script src="media://koowa/js/tabs.js" />';
            self::$_loaded['tabs'] = true;
        }

        $id      = strtolower($config->id);
        $attribs = $this->_buildAttributes($config->attribs);
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
     * @param   array   An optional array with configuration options
     * @return  string  Html
     */
    public function endPane($config = array())
    {
        return '</dl>';
    }

    /**
     * Creates a tab panel with title and starts that panel
     *
     * @param   array   An optional array with configuration options
     * @return  string  Html
     */
    public function startPanel( $config = array())
    {
        $config = new Config($config);
        $config->append(array(
            'title'     => '',
            'attribs'   => array(),
            'options'   => array(),
            'translate' => true
        ));

        $title   = $config->translate ? \JText::_($config->title) : $config->title;
        $attribs = $this->_buildAttributes($config->attribs);

        return '<dt '.$attribs.'><span>'.$title.'</span></dt><dd>';
    }

    /**
     * Ends a tab page
     *
     * @param   array   An optional array with configuration options
     * @return  string  Html
     */
    public function endPanel($config = array())
    {
        return '</dd>';
    }
}