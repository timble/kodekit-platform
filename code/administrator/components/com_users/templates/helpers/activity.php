<?php
/**
 * @version		$Id$
 * @category	Nooku
 * @package     Nooku_Server
 * @subpackage  Users
 * @copyright	Copyright (C) 2010 Timble CVBA and Contributors. (http://www.timble.net)
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		http://www.nooku.org
 */

/**
 * Log Template Helper Class
 *
 * @author      Johan Janssens <http://nooku.assembla.com/profile/johanjanssens>
 * @category	Nooku
 * @package    	Nooku_Server
 * @subpackage 	Users
 */

class ComUsersTemplateHelperActivity extends ComActivitiesTemplateHelperActivity
{
    public function message($config = array())
	{
	    $config = new KConfig($config);
		$config->append(array(
			'row'      => ''
		));
		
		$row = $config->row;
		
		if($row->action == 'login' || $row->action == 'logout') 
		{    
		    $item = $this->getTemplate()->getView()->createRoute('option='.$row->type.'_'.$row->package.'&view='.$row->name.'&id='.$row->row);
		    
		    $message   = '<a href="'.$item.'">'.$row->title.'</a>'; 
		    $message  .= ' <span class="action">'.$row->status.'</span>';
		}
		else $message = parent::message($config);
		
		return $message;
	}
}