<?php
/**
 * @version     $Id: link.php -1 1970-01-01 00:00:00Z  $
 * @package     Nooku_Server
 * @subpackage  Extensions
 * @copyright   Copyright (C) 2007 - 2012 Johan Janssens. All rights reserved.
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://www.nooku.org
 */

/**
 * Module Template Filter Class
 *
 * Filter will parse elements of the form <html:modules position="[position]" /> and render the modules that are
 * available for this position.
 *
 * Filter will parse elements of the form <html:module position="[position]">[content]</module> and inject the
 * content into the module position.
 *
 * @author    	Johan Janssens <http://nooku.assembla.com/profile/johanjanssens>
 * @package     Nooku_Server
 * @subpackage  Pages
 */
class ComPagesTemplateFilterModule extends KTemplateFilterAbstract implements KTemplateFilterWrite
{
    /**
     * Modules
     *
     * @var ComPagesDatabaseRowsetModules
     */
    protected $_modules;

    /**
     * Parse <khtml:modules /> and <khtml:modules></khtml:modules> tags
     *
     * @param string Block of text to parse
     * @return ComPagesTemplateFilterModule
     */
    public function write(&$text)
    {
        $this->_parseModuleTags($text);
        $this->_parseModulesTags($text);

        return $this;
    }

    /**
     * Parse <ktml:module></ktml:module> tags
     *
     * @param string Block of text to parse
     * @@return ComPagesDatabaseRowsetModules The rowset object.
     */
    public function _parseModuleTags(&$text)
    {
        $matches = array();
        if(preg_match_all('#<ktml:module\s+([^>]*)>(.*)</ktml:module>#siU', $text, $matches))
        {
            foreach($matches[0] as $key => $match)
            {
                //Create attributes array
                $defaults = array(
                    'params'	=> '',
                    'title'		=> '',
                    'class'		=> '',
                    'position'  => ''
                );

                $attributes = array_merge($defaults, $this->_parseAttributes($matches[1][$key]));

                //Create module object
                $values = array(
                    'id'         => uniqid(),
                    'content'    => $matches[2][$key],
                    'position'   => $attributes['position'],
                    'params'     => $attributes['params'],
                    'title'      => $attributes['title'],
                    'name'       => 'mod_default',
                    'identifier' => $this->getIdentifier('com://site/default.module.default.html'),
                    'attribs'    => array_diff_key($attributes, $defaults)
                );

                $this->_loadModules()->addData(array($values), false);
            }

            //Remove the <khtml:module></khtml:module> tags
            $text = str_replace($matches[0], '', $text);
        }
    }

    /**
     * Parse <khtml:modules /> and <khtml:modules></khtml:modules> tags
     *
     * @param string Block of text to parse
     * @return ComPagesTemplateFilterModule
     */
    public function _parseModulesTags(&$text)
    {
        $replace = array();
        $matches = array();
        // <ktml:modules position="[position]" />
        if(preg_match_all('#<ktml:modules\s+position="([^"]+)"(.*)\/>#iU', $text, $matches))
        {
            $count = count($matches[1]);

            for($i = 0; $i < $count; $i++)
            {
                $position    = $matches[1][$i];
                $attribs     = $this->_parseAttributes( $matches[2][$i] );

                $modules = $this->_loadModules()->find(array('position' => $position));
                $replace[$i] = $this->_renderModules($modules, $attribs);
            }

            $text = str_replace($matches[0], $replace, $text);
        }

        $replace = array();
        $matches = array();
        // <ktml:modules position="[position]"></khtml:modules>
        if(preg_match_all('#<ktml:modules\s+position="([^"]+)"(.*)>(.*)</ktml:modules>#siU', $text, $matches))
        {
            $count = count($matches[1]);

            for($i = 0; $i < $count; $i++)
            {
                $position    = $matches[1][$i];
                $attribs     = $this->_parseAttributes( $matches[2][$i] );

                $modules = $this->_loadModules()->find(array('position' => $position));
                $replace[$i] = $this->_renderModules($modules, $attribs);

                if(!empty($replace[$i])) {
                    $replace[$i] = str_replace('<ktml:content />', $replace[$i], $matches[3][$i]);
                }
            }

            $text = str_replace($matches[0], $replace, $text);
        }
    }

    /**
     * Get modules
     *
     * @return ComPagesDatabaseRowsetModules The rowset object.
     */
    public function _loadModules()
    {
        if(!$this->_modules)
        {
            $page = $this->getService('application.pages')->getActive();

            // Select published modules
            $modules = $this->getService('com://admin/pages.model.modules')
                ->application('site')
                ->page($page->id)
                ->published(true)
                ->access((int) JFactory::getUser()->aid)
                ->getList();

            $this->_modules = $modules;
        }

        return $this->_modules;
    }

    /**
     * Render the modules
     *
     * @param string $position  The modules position to render
     * @param array  $attribs   List of module attributes
     * @return string   The rendered modules
     */
    public function _renderModules($modules, $attribs = array())
    {
        $html  = '';
        $count = 1;
        foreach($modules as $module)
        {
            //Set the chrome styles
            if(isset($attribs['chrome'])) {
                $module->chrome  = explode(' ', $attribs['chrome']);
            }

            //Set the module attributes
            if($count == 1) {
                $attribs['rel']['first'] = 'first';
            }

            if($count == count($modules)) {
                $attribs['rel']['last'] = 'last';
            }

            $module->attribs = array_merge($module->attribs, $attribs);

            //Render the module
            $content = $this->getService($module->identifier)->data(array('module' => $module))->display();

            //Prepend or append the module
            if(isset($module->attribs['content']) && $module->attribs['content'] == 'prepend') {
                $html = $content.$html;
            } else {
                $html = $html.$content;
            }

            $count++;
        }

        return $html;
    }
}