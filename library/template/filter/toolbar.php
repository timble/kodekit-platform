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
 * Toolbar Template Filter
 *
 * Filter will parse <ktml:toolbar type="[type]'> tags and replace them with the actual toolbar html by rendering
 * the toolbar helper for the specific toolbar type.
 *
 * @author  Johan Janssens <http://nooku.assembla.com/profile/johanjanssens>
 * @package Nooku\Library\Template
 */
class TemplateFilterToolbar extends TemplateFilterAbstract implements TemplateFilterRenderer
{
    /**
     * Toolbars to render such as actionbar, menubar, ...
     *
     * @var array
     */
    protected $_toolbars;

    /**
     * Constructor
     *
     * @param  ObjectConfig $config Configuration options
     */
    public function __construct(ObjectConfig $config)
    {
        parent::__construct($config);

        $this->setToolbars(ObjectConfig::unbox($config->toolbars));
    }

    /**
     * Initializes the options for the object
     *
     * Called from {@link __construct()} as a first step of object instantiation.
     *
     * @param   ObjectConfig $config Configuration options
     * @return  void
     */
    protected function _initialize(ObjectConfig $config)
    {
        $config->append(array(
            'toolbars' => array(),
        ));

        parent::_initialize($config);
    }

    /**
     * Get the list of toolbars to be rendered
     *
     * @return array
     */
    public function getToolbars()
    {
        return $this->_toolbars;
    }

    /**
     * Set the toolbars to render
     *
     * @param array $toolbars
     * @return $this
     */
    public function setToolbars(array $toolbars)
    {
        $this->_toolbars = $toolbars;
        return $this;
    }

    /**
     * Returns the menu bar instance
     *
     * @return ControllerToolbarInterface
     */
    public function getToolbar($type = 'actionbar')
    {
        return isset($this->_toolbars[$type]) ? $this->_toolbars[$type] : null;
    }

    /**
     * Sets the menu bar instance
     *
     * @param  ControllerToolbarInterface $toolbar
     * @return TemplateFilterToolbar
     */
    public function setToolbar(ControllerToolbarInterface $toolbar)
    {
        $this->_toolbars[$toolbar->getType()] = $toolbar;
        return $this;
    }

    /**
     * Replace/push the toolbars
     *
     * @param string $text Block of text to parse
     * @return TemplateFilterToolbar
     */
    public function render(&$text)
    {
        $matches = array();

        if(preg_match_all('#<ktml:toolbar([^>]*)>#siU', $text, $matches))
        {
            foreach($matches[0] as $key => $match)
            {
                $attributes = $this->parseAttributes($matches[1][$key]);

                //Create attributes array
                $config = new ObjectConfig($attributes);
                $config->append(array(
                    'type'  => 'actionbar',
                ));

                $html = '';
                if($toolbar = $this->getToolbar($config->type))
                {
                    $config->toolbar = $toolbar; //set the toolbar in the config

                    $html = $this->getTemplate()
                                 ->getHelper($config->type)
                                 ->render($config);
                }

                //Remove placeholder
                $text = str_replace($match, $html, $text);
            }
        }

        return $this;
    }
}