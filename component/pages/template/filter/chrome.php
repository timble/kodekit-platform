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
 * Chrome Template Filter
 *
 * @author  Johan Janssens <http://nooku.assembla.com/profile/johanjanssens>
 * @package Nooku\Component\Pages
 */
class TemplateFilterChrome extends Library\TemplateFilterAbstract implements Library\TemplateFilterRenderer, Library\ObjectInstantiable
{
    /**
     * The chrome styles
     *
     * @var array
     */
    protected $_styles;

    /**
     * Constructor.
     *
     * @param ObjectConfig $config  An optional Library\ObjectConfig object with configuration options
     */
    public function __construct( Library\ObjectConfig $config )
    {
        parent::__construct($config);

        $this->_styles = $config->styles;
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
            'styles'  => array(),
        ));

        parent::_initialize($config);
    }

    /**
     * Check for overrides of the filter
     *
     * @param   Library\ObjectConfig         	        $config  An optional Library\ObjectConfig object with configuration options
     * @param 	Library\ObjectManagerInterface	$manager A Library\ObjectManagerInterface object
     * @return  TemplateFilterChrome
     */
    public static function getInstance(Library\ObjectConfig $config, Library\ObjectManagerInterface $manager)
    {
        $identifier = clone $config->object_identifier;
        $identifier->package = $config->module->package;

        $identifier = $manager->getIdentifier($identifier);

        if(file_exists($identifier->classpath)) {
            $classname = $identifier->classname;
        } else {
            $classname = $config->object_identifier->classname;
        }

        $instance  = new $classname($config);
        return $instance;
    }

    /**
     * Append a module chrome style
     *
     * @return TemplateFilterChrome
     */
    public function appendStyle($style)
    {
        $this->_styles[] = $style;
        return $this;
    }

    /**
     * Prepend a module chrome style
     *
     * @return TemplateFilterChrome
     */
    public function prependStyle($style)
    {
        array_unshift($this->_styles, $style);
        return $this;
    }

    /**
     * Get the module chrome styles
     *
     * @return array
     */
    public function getStyles()
    {
        return $this->_styles;
    }

    /**
     * Render the module chrome
     *
     * @param string $text Block of text to parse
     * @return void
     */
    public function render(&$text)
    {
        $data = (object) $this->getTemplate()->getData();

        foreach($this->_styles as $style)
        {
            $method = '_style'.ucfirst($style);

            // Apply chrome and render module
            if (method_exists($this, $method))
            {
                $data->module->content = $text;
                $text = $this->$method($data->module);
            }
        }
    }

    protected function _styleWrapped($module)
    {
        $html = '';
        if (!empty ($module->content))
        {
            $html .= '<div class="module '.$module->name.'">';
            $html .= $module->content;
            $html .= '</div>';
        }

        return $html;
    }

    protected function _styleAccordion($module)
    {
        $accordion = $this->getTemplate()->getHelper('accordion');

        $config = array(
            'title'     => $this->translate( $module->title ),
            'id'        => 'module' . $module->id,
            'translate' => false
        );

        $html = '';
        if(isset($module->attribs['rel']) && isset($module->attribs['rel']['first'])) {
            $html .= $accordion->startPane();
        }

        $html .= $accordion->startPanel( $config);
        $html .= $module->content;
        $html .= $accordion->endPanel();

        if(isset($module->attribs['rel']) && isset($module->attribs['rel']['last'])) {
            $html .= $accordion->endPane();
        }

        return $html;
    }

    protected function _styleTabs($module)
    {
        $tabs = $this->getTemplate()->getHelper('tabs');

        $config = array(
            'title'     => $this->translate( $module->title ),
            'id'        => 'module' . $module->id,
            'translate' => false
        );

        $html = '';
        if(isset($module->attribs['rel']) && isset($module->attribs['rel']['first'])) {
            $html .= $tabs->startPane();
        }

        $html .= $tabs->startPanel( $config);
        $html .= $module->content;
        $html .= $tabs->endPanel();

        if(isset($module->attribs['rel']) && isset($module->attribs['rel']['last'])) {
            $html .= $tabs->endPane();
        }

        return $html;
    }

    protected function _styleOutline($module)
    {
        $html = '';

        $html .= '<style>';
        $html .= '.mod-preview-info { padding: 2px 4px 2px 4px; border: 1px solid black; position: absolute; background-color: white; color: red;opacity: .80; filter: alpha(opacity=80); -moz-opactiy: .80; }';
        $html .=  '.mod-preview-wrapper { background-color:#eee;  border: 1px dotted black; color:#700; opacity: .50; filter: alpha(opacity=50); -moz-opactiy: .50;}';

        $html .= '<div class="mod-preview">';
        $html .= '<div class="mod-preview-info">'.$module->position."[".$module->style."]".'</div>';
        $html .= '<div class="mod-preview-wrapper">';
        $html .= $module->content;
        $html .= '</div>';
        $html .= '</div>';

        return $html;
    }
}