<?php
/**
 * Nooku Framework - http://www.nooku.org
 *
 * @copyright	Copyright (C) 2011 - 2013 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		git://git.assembla.com/nooku-framework.git for the canonical source repository
 */

namespace Nooku\Component\Pages;

use Nooku\Library;

/**
 * Module Template Filter
 *
 * Filter will parse elements of the form <html:modules position="[position]"> and render the modules that are
 * available for this position.
 *
 * Filter will parse elements of the form <html:module position="[position]">[content]</module> and inject the
 * content into the module position.
 *
 * @author  Johan Janssens <http://nooku.assembla.com/profile/johanjanssens>
 * @package Nooku\Component\Pages
 */
class TemplateFilterModule extends Library\TemplateFilterAbstract implements Library\TemplateFilterRenderer
{
    /**
     * Database rowset or identifier
     *
     * @var	string|object
     */
    protected $_modules;

    /**
     * Constructor.
     *
     * @param  ObjectConfig $config  An optional Library\ObjectConfig object with configuration options
     */
    public function __construct(Library\ObjectConfig $config)
    {
        parent::__construct($config);

        $this->_modules = $config->modules;
    }

    /**
     * Initializes the options for the object
     *
     * Called from {@link __construct()} as a first step of object instantiation.
     *
     * @param  ObjectConfig $config  An optional Library\ObjectConfig object with configuration options
     * @return void
     */
    protected function _initialize(Library\ObjectConfig $config)
    {
        $config->append(array(
            'modules'  => null,
            'priority' => Library\TemplateFilter::PRIORITY_LOW,
        ));

        parent::_initialize($config);
    }

    /**
     * Parse <khtml:modules /> and <khtml:modules></khtml:modules> tags
     *
     * @param string $text Block of text to parse
     * @return void
     */
    public function render(&$text)
    {
        $this->_parseModuleTags($text);
        $this->_parseModulesTags($text);
    }

    /**
     * Get the modules
     *
     * @throws	\UnexpectedValueException	If the request doesn't implement the Library\DatabaseRowsetInterface
     * @return Library\DatabaseRowsetInterface
     */
    public function getModules()
    {
        if(!$this->_modules instanceof Library\DatabaseRowsetInterface)
        {
            $this->_modules = $this->getObject($this->_modules);

            if(!$this->_modules instanceof Library\DatabaseRowsetInterface)
            {
                throw new \UnexpectedValueException(
                    'Modules: '.get_class($this->_modules).' does not implement Library\DatabaseRowsetInterface'
                );
            }
        }

        return $this->_modules;
    }

    /**
     * Parse <ktml:module></ktml:module> tags
     *
     * @param string $text Block of text to parse
     */
    protected function _parseModuleTags(&$text)
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

                $attributes = array_merge($defaults, $this->parseAttributes($matches[1][$key]));

                //Create module object
                $values = array(
                    'id'         => uniqid(),
                    'content'    => $matches[2][$key],
                    'position'   => $attributes['position'],
                    'params'     => $attributes['params'],
                    'title'      => $attributes['title'],
                    'name'       => 'mod_dynamic',
                    'identifier' => $this->getIdentifier('com:pages.module.dynamic.html'),
                    'attribs'    => array_diff_key($attributes, $defaults)
                );

                $this->getModules()->addRow(array($values), Library\Database::STATUS_LOADED);
            }

            //Remove the <khtml:module></khtml:module> tags
            $text = str_replace($matches[0], '', $text);
        }
    }

    /**
     * Parse <ktml:modules> and <ktml:modules></ktml:modules> tags
     *
     * @param string $text Block of text to parse
     */
    protected function _parseModulesTags(&$text)
    {
        $replace = array();
        $matches = array();
        // <ktml:modules position="[position]"></khtml:modules>
        if(preg_match_all('#<ktml:modules\s+position="([^"]+)"(.*)>(.*)</ktml:modules>#siU', $text, $matches))
        {
            $count = count($matches[1]);

            for($i = 0; $i < $count; $i++)
            {
                $position    = $matches[1][$i];
                $attribs     = $this->parseAttributes( $matches[2][$i] );

                $modules = $this->getModules()->find(array('position' => $position));
                $replace[$i] = $this->_renderModules($modules, $attribs);

                if(!empty($replace[$i])) {
                    $replace[$i] = str_replace('<ktml:modules:content>', $replace[$i], $matches[3][$i]);
                }
            }

            $text = str_replace($matches[0], $replace, $text);
        }

        $replace = array();
        $matches = array();
        // <ktml:modules position="[position]">
        if(preg_match_all('#<ktml:modules\s+position="([^"]+)"(.*)>#iU', $text, $matches))
        {
            $count = count($matches[1]);

            for($i = 0; $i < $count; $i++)
            {
                $position    = $matches[1][$i];
                $attribs     = $this->parseAttributes( $matches[2][$i] );

                $modules = $this->getModules()->find(array('position' => $position));
                $replace[$i] = $this->_renderModules($modules, $attribs);
            }

            $text = str_replace($matches[0], $replace, $text);
        }
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
            $content = $this->getObject($module->identifier)
                ->data(array('module' => $module))
                ->content($module->content)
                ->render();

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