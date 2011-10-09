<?php
/**
 * @version     $Id$
 * @category	Nooku
 * @package     Nooku_Components
 * @subpackage  Installer
 * @copyright   Copyright (C) 2011 Timble CVBA and Contributors. (http://www.timble.net).
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://www.nooku.org
 */


/**
 * Template Sidebar Helper
 *
 * @author      Stian Didriksen <http://nooku.assembla.com/profile/stiandidriksen>
 * @category    Nooku
 * @package     Nooku_Components
 * @subpackage  Installer
 */
class ComInstallerTemplateHelperSidebar extends ComDefaultTemplateHelperMenubar
{
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
    		'sidebar' => null,
        ));
        
        parent::_initialize($config);
    }
    
 	/**
     * Render the sidebar 
     *
     * @param   array   An optional array with configuration options
     * @return  string  Html
     */
    public function render($config = array())
    {
        $config = new KConfig($config);
        $config->append(array(
        	'sidebar' => null,
        	'title'   => 'Types'
        ));
		
		$html = '<div id="sidebar">';
        
        $html .= '<h3>'.JText::_($config->title).'</h3>';
        
        $html .= '<ul>';
	    foreach ($config->sidebar->getCommands() as $command) 
	    {
	        if($command->active) {
	            $html .= '<li class="active">';
	        } else {
	            $html .= '<li>';
	        }
            $html .= $this->command(array('command' => $command)); 
            $html .= '</li>';  
        }
        
        $html .= '</ul>';
        $html .= '</div>';
		
		return $html;
    }
}