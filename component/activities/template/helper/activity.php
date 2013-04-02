<?php
/**
 * Nooku Framework - http://www.nooku.org
 *
 * @copyright	Copyright (C) 2011 - 2013 Timble CVBA and Contributors. (http://www.timble.net)
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		git://git.assembla.com/nooku-framework.git
 */

namespace Nooku\Component\Activities;

use Nooku\Library;

/**
 * Activity Template Helper
 *
 * @author  Israel Canasa <http://nooku.assembla.com/profile/israelcanasa>
 * @package Nooku\Component\Activities
 */
class TemplateHelperActivity extends Library\TemplateHelperDefault implements Library\ServiceInstantiatable
{
	/**
     * Check for overrides of the helper
     *
     * @param   Library\Config         	        $config  An optional Library\Config object with configuration options
     * @param 	Library\ServiceManagerInterface	$manager A Library\ServiceManagerInterface object
     * @return  TemplateHelperActivity
     */
    public static function getInstance(Library\Config $config, Library\ServiceManagerInterface $manager)
    {
        $identifier = clone $config->service_identifier;
        $identifier->package = $config->row->package;
       
        $identifier = $manager->getIdentifier($identifier);
        
        if(file_exists($identifier->filepath)) {
            $classname = $identifier->classname;    
        } else {
            $classname = $config->service_identifier->classname;
        }
        
        $instance  = new $classname($config);               
        return $instance;
    }
    
    public function message($config = array())
	{
	    $config = new Library\Config($config);
		$config->append(array(
			'row'      => ''
		));
	
		$row  = $config->row;
		$item = $this->getTemplate()->getView()->getRoute('option='.$row->type.'_'.$row->package.'&view='.$row->name.'&id='.$row->row);
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