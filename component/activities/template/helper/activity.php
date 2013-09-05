<?php
/**
 * Nooku Framework - http://www.nooku.org
 *
 * @copyright	Copyright (C) 2011 - 2013 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		git://git.assembla.com/nooku-framework.git for the canonical source repository
 */

namespace Nooku\Component\Activities;

use Nooku\Library;

/**
 * Activity Template Helper
 *
 * @author  Israel Canasa <http://nooku.assembla.com/profile/israelcanasa>
 * @package Nooku\Component\Activities
 */
class TemplateHelperActivity extends Library\TemplateHelperDefault implements Library\ObjectInstantiable
{
	/**
     * Check for overrides of the helper
     *
     * @param   Library\ObjectConfig         	        $config  An optional Library\ObjectConfig object with configuration options
     * @param 	Library\ObjectManagerInterface	$manager A Library\ObjectManagerInterface object
     * @return  TemplateHelperActivity
     */
    public static function getInstance(Library\ObjectConfig $config, Library\ObjectManagerInterface $manager)
    {
        $identifier = clone $config->object_identifier;
        $identifier->package = $config->row->package;
       
        $identifier = $manager->getIdentifier($identifier);
        
        if(file_exists($identifier->classpath)) {
            $classname = $identifier->classname;    
        } else {
            $classname = $config->object_identifier->classname;
        }
        
        $instance  = new $classname($config);               
        return $instance;
    }
    
    public function message($config = array())
	{
	    $config = new Library\ObjectConfig($config);
		$config->append(array(
			'row'      => ''
		));
	
		$row  = $config->row;
		$item = $this->getTemplate()->getView()->getRoute('option=com_'.$row->package.'&view='.$row->name.'&id='.$row->row);
		$user = $this->getTemplate()->getView()->getRoute('option=com_users&view=user&id='.$row->created_by); 
		
		$message   = '<a href="'.$user.'">'.$row->created_by_name.'</a> '; 
		$message  .= $row->status;
       
		if ($row->status != 'trashed') {
			$message .= ' <a href="'.$item.'">'.$row->title.'</a>';
		} else {
			$message .= ' <span class="trashed">'.$row->title.'</span>';
		}
		
		$message .= ' '.$row->name; 
		
		return $message;
	}
}