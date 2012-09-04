<?php
/**
 * @version      $Id: chrome.php 4839 2012-08-23 22:48:57Z johanjanssens $
 * @package      Nooku_Components
 * @subpackage	 Default
 * @copyright    Copyright (C) 2007 - 2012 Johan Janssens. All rights reserved.
 * @license      GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link 		 http://www.nooku.org
 */

/**
 * Module Chrome Filter
 *
 * @author		Johan Janssens <johan@nooku.org>
 * @package      Nooku_Module
 * @subpackage   Default
 */
class ComDefaultTemplateFilterChrome extends KTemplateFilterAbstract implements KTemplateFilterWrite
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
     * @param   object  An optional KConfig object with configuration options
     */
    public function __construct( KConfig $config )
    {
        parent::__construct($config);

        $this->_styles = $config->styles;
    }

    /**
     * Initializes the options for the object
     *
     * Called from {@link __construct()} as a first step of object instantiation.
     *
     * @param   object  An optional KConfig object with configuration options
     * @return void
     */
    protected function _initialize(KConfig $config)
    {
        $config->append(array(
            'styles'  => array(),
        ));

        parent::_initialize($config);
    }

    /**
     * Append a module chrome style
     *
     * @return ModDefaultTemplateFilterChrome
     */
    public function appendStyle($style)
    {
        $this->_styles[] = $style;
        return $this;
    }

    /**
     * Prepend a module chrome style
     *
     * @return ModDefaultTemplateFilterChrome
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
     * @param string Block of text to parse
     * @return ModDefaultFilterChrome
     */
    public function write(&$text)
    {
        $data = (object) $this->getTemplate()->getData();

        foreach($data->module->chrome as $style)
        {
            $method = '_style'.ucfirst($style);

            // Apply chrome and render module
            if (method_exists($this, $method))
            {
                $data->module->content = $text;
                $text = $this->$method($data->module);
            }
        }

        return $this;
    }

    protected function _styleWrapped($module)
    {
        $html = '';
        if (!empty ($module->content))
        {
            $html .= '<div class="module">';
            if ($module->showtitle) {
                $html .= '<h3>'.$module->title.'</h3>';
            }

            $html .= $module->content;
            $html .= '</div>';
        }

        return $html;
    }

    protected function _styleAccordion($module)
    {
        $accordion = $this->getTemplate()->getHelper('accordion');

        $config = array(
            'title'     => JText::_( $module->title ),
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
            'title'     => JText::_( $module->title ),
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