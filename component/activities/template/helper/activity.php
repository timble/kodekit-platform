<?php
/**
 * Nooku Platform - http://www.nooku.org/platform
 *
 * @copyright	Copyright (C) 2011 - 2014 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		https://github.com/nooku/nooku-platform for the canonical source repository
 */

namespace Nooku\Component\Activities;

use Nooku\Library;

/**
 * Activity Template Helper
 *
 * @author  Israel Canasa <http://github.com/raeldc>
 * @package Nooku\Component\Activities
 */
class TemplateHelperActivity extends Library\TemplateHelperAbstract implements Library\ObjectInstantiable
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
        $identifier = $config->object_identifier->toArray();
        $identifier['package'] = $config->entity->package;
       
        $identifier = $manager->getIdentifier($identifier);

        if($manager->getClass($identifier, false)) {
            $class = $manager->getClass($identifier);
        } else {
            $class = $manager->getClass($config->object_identifier);
        }
        
        $instance  = new $class($config);
        return $instance;
    }
    
    public function message($config = array())
	{
	    $config = new Library\ObjectConfig($config);
		$config->append(array(
			'entity'      => ''
		));
	
		$entity  = $config->entity;
		$item = $this->getTemplate()->route('component='.$entity->package.'&view='.$entity->name.'&id='.$entity->row);
		$user = $this->getTemplate()->route('component=users&view=user&id='.$entity->created_by);
		
		$message   = '<a href="'.$user.'">'.$entity->getAuthor()->getName().'</a> ';
		$message  .= $entity->status;
       
		if ($entity->status != 'trashed') {
			$message .= ' <a href="'.$item.'">'.$entity->title.'</a>';
		} else {
			$message .= ' <span class="trashed">'.$entity->title.'</span>';
		}
		
		$message .= ' '.$entity->name;
		
		return $message;
	}
}