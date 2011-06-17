<?php
/**
 * @version     $Id: behavior.php 3364 2011-05-25 21:07:41Z johanjanssens $
 * @category	Nooku
 * @package     Nooku_Components
 * @subpackage  Default
 * @copyright   Copyright (C) 2007 - 2010 Johan Janssens. All rights reserved.
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://www.nooku.org
 */


/**
 * Template Toolbar Helper
.*
 * @author      Johan Janssens <johan@nooku.org>
 * @category    Nooku
 * @package     Nooku_Components
 * @subpackage  Default
 * @uses        KFactory
 */
class ComDefaultTemplateHelperToolbar extends KTemplateHelperAbstract
{
	/**
	 * A toolbar object
	 * 
	 * @var KToolbar
	 */
    protected $_toolbar;
    
	/**
	 * Constructor
	 *
	 * Prevent creating instances of this class by making the contructor private
	 * 
	 * @param 	object 	An optional KConfig object with configuration options
	 */
	public function __construct(KConfig $config)
	{
		parent::__construct($config);
	
		$this->_toolbar = $config->toolbar;
	}
    
	/**
     * Initializes the options for the object
     *
     * Called from {@link __construct()} as a first step of object instantiation.
     *
     * @param 	object 	An optional KConfig object with configuration options.
     * @return 	void
     */
    protected function _initialize(KConfig $config)
    {
    	$config->append(array(
    		'toolbar' => null,
        ));
        
        parent::_initialize($config);
    }
	
	/**
     * Render the toolbar title
     * 
     * @param   array   An optional array with configuration options
     * @return  string  Html
     */
    public function title($config = array())
    {
        $config = new KConfig($config);
        
        $html = '<div class="header pagetitle icon-48-'.$this->_toolbar->getIcon().'">';
        
        if (version_compare(JVERSION,'1.6.0','ge')) {
			$html .= '<h2>'.JText::_($this->_toolbar->getTitle()).'</h2>';
        } else {
            $html .= $this->_toolbar->getTitle();
        }
		
		$html .= '</div>';
        
        return $html;
    }
    
    /**
     * Render the toolbar 
     *
     * @param   array   An optional array with configuration options
     * @return  string  Html
     */
    public function toolbar($config = array())
    {
        $config = new KConfig($config);
        
        if (version_compare(JVERSION,'1.6.0','ge')) {
		  $html	= '<div class="toolbar-list" id="toolbar-'.$this->_toolbar->getName().'">';
        } else {
          $html = '<div class="toolbar" id="toolbar-'.$this->_toolbar->getName().'">';
        }
        
        $html .= '<table class="toolbar">';
	    $html .= '<tr>';
	    foreach ($this->_toolbar as $command) 
	    {
            if($command->getName() != 'seperator') { 
	            $html .= $this->command(array('command' => $command));   
            } else {
                $html .= $this->seperator(array('command' => $command));
            }
       	}
		$html .= '</tr>';
		$html .= '</table>';
		
		$html .= '</div>';
		
		return $html;
    }
    
    /**
     * Render a toolbar command
     *
     * @param   array   An optional array with configuration options
     * @return  string  Html
     */
    public function command($config = array())
    {
        $config = new KConfig($config);
        $config->append(array(
        	'command' => NULL
        ));
        
        $command = $config->command;
          
        $id = 'toolbar-'.$command->id;
		$command->attribs->class = implode(" ", KConfig::toData($command->attribs->class));
			
        $html  = '<td class="button" id="'.$id.'">';
        $html .= '	<a '.KHelperArray::toString($command->attribs).'>';
        $html .= '		<span class="'.$command->icon.'" title="'.JText::_($command->text).'"></span>';
       	$html .= JText::_($command->label);
       	$html .= '   </a>';
        $html .= '</td>';
       	
    	return $html;
    }
    
	/**
     * Render a seperator
     *
     * @param   array   An optional array with configuration options
     * @return  string  Html
     */
    public function seperator($config = array())
    {
        $config = new KConfig($config);
        $config->append(array(
        	'command' => NULL
        ));
        
        $command = $config->command;
          
       	$html = '</tr></table><table class="toolbar"><tr><td class="divider"></td></tr></table><table class="toolbar"><tr>';
       	
    	return $html;
    }
}