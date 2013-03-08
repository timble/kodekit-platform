<?php
/**
 * Nooku Framework - http://www.nooku.org
 *
 * @copyright	Copyright (C) 2011 - 2013 Timble CVBA and Contributors. (http://www.timble.net)
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		git://git.assembla.com/nooku-framework.git
 */

use Nooku\Framework;

/**
 * Activity Template Helper
 *
 * @author  Israel Canasa <http://nooku.assembla.com/profile/israelcanasa>
 * @package Nooku\Component\Activities
 */
class ComActivitiesTemplateHelperActivity extends Framework\TemplateHelperDefault implements Framework\ServiceInstantiatable
{
	/**
     * Check for overrides of the helper
     *
     * @param   Framework\Config         	        $config  An optional Framework\Config object with configuration options
     * @param 	Framework\ServiceManagerInterface	$manager A Framework\ServiceManagerInterface object
     * @return ComActivitiesTemplateHelperActivity
     */
    public static function getInstance(Framework\Config $config, Framework\ServiceManagerInterface $manager)
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
	    $config = new Framework\Config($config);
		$config->append(array(
			'row'      => ''
		));
	
		$row  = $config->row;
		$item = $this->getTemplate()->getView()->getRoute('option='.$row->type.'_'.$row->package.'&view='.$row->name.'&id='.$row->row);
		$user = $this->getTemplate()->getView()->getRoute('option=com_users&view=user&id='.$row->created_by); 
		
		$message   = '<a href="'.$user.'">'.$row->created_by_name.'</a> '; 
		$message  .= $row->status;
       
		if ($row->status != 'deleted') {
			$message .= ' <a href="'.$item.'">'.$row->title.'</a>';
		} else {
			$message .= ' <span class="deleted">'.$row->title.'</span>';
		}
		
		$message .= ' '.$row->name; 
		
		return $message;
	}
}