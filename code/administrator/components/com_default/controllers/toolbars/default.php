<?php
/**
 * @version   	$Id$
 * @category	Nooku
 * @package     Nooku_Components
 * @subpackage  Default
 * @copyright  	Copyright (C) 2007 - 2010 Johan Janssens. All rights reserved.
 * @license   	GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://www.nooku.org
 */

/**
 * Default Toolbar
.*
 * @author      Johan Janssens <johan@nooku.org>
 * @category    Nooku
 * @package     Nooku_Components
 * @subpackage  Default
 */
class ComDefaultControllerToolbarDefault extends KControllerToolbarDefault
{
	/**
     * Constructor
     *
     * @param   object  An optional KConfig object with configuration options
     */
    public function __construct(KConfig $config)
    {
        parent::__construct($config);
       
        $name  = $this->_identifier->name;
        
        if(KInflector::isPlural($name))
        {        
            //Insert the default commands
            $this->append('new')
                 ->append('delete');    
        }
        else
        {
            //Insert the default commands
            $this->append('save')
                 ->append('apply')
                 ->append('cancel');
            
            //Set the title
            $name  = ucfirst($this->getName());  
            $state = $this->getController()->getModel()->getState();   
            $title = $state->isUnique() ?  'Edit '.$name : 'New '.$name;
            
            $this->setTitle($title);
        }
    }
    
    protected function _commandAction(KControllerToolbarCommand $command)
    {
        $command->append(array(
            'attribs'    => array(
                'data-action'  => $command->getName()
            )
        ));
    }
  
    protected function _commandNew(KControllerToolbarCommand $command)
    {
        $option = KRequest::get('get.option', 'cmd');
        $view   = KInflector::singularize(KRequest::get('get.view', 'cmd'));
        
        $command->append(array(
            'attribs' => array(
                'href'     => JRoute::_( 'index.php?option='.$option.'&view='.$view)
            )
        ));
    }
    
    protected function _commandCancel(KControllerToolbarCommand $command)
    {  
        $command->append(array(
        	'attribs' => array(
                'data-action' 	  => 'cancel',
        		'data-novalidate' => 'novalidate'
            )
        ));	
    }
    
    protected function _commandEnable(KControllerToolbarCommand $command)
    {
        $command->icon = 'icon-32-publish'; 
        
        $command->append(array(
            'attribs' => array(
                'data-action' => 'edit',
                'data-data'   => '{enabled:1}'
            )
        ));
    }
    
    protected function _commandDisable(KControllerToolbarCommand $command)
    {
        $command->icon = 'icon-32-unpublish';
        
        $command->append(array(   
            'attribs' => array(
                'data-action' => 'edit',
                'data-data'   => '{enabled:0}'
            )
        ));
    }
    
    protected function _commandExport(KControllerToolbarCommand $command)
    {
        $url = clone KRequest::url();
        $query = parse_str($url->getQuery(), $vars);
        
        unset($vars['limit']);
        unset($vars['offset']);
        
        $vars['format'] = 'csv';
        $url->setQuery(http_build_query($vars));
        
        $command->append(array(
            'attribs' => array(
                'href' =>  (string) $url
            )
        ));
    }
      
    protected function _commandPreferences(KControllerToolbarCommand $command)
    { 
        $command->append(array(
            'width'   => '640',
            'height'  => '480',
        ))->append(array(
            'attribs' => array(
                'class' => array('modal'),
                'href'  => 'index.php?option=com_config&controller=component&component=com_'.$this->_identifier->package,
                'rel'   => '{handler: \'iframe\', size: {x: '.$command->width.', y: '.$command->height.'}}'
            )
        ));
    }
}