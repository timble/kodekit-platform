<?php
/**
 * Nooku Framework - http://www.nooku.org
 *
 * @copyright	Copyright (C) 2011 - 2013 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		git://git.assembla.com/nooku-framework.git for the canonical source repository
 */

use Nooku\Library;
use Nooku\Component\Activities;

/**
 * Activity Template Helper
 *
 * @author  Johan Janssens <http://nooku.assembla.com/profile/johanjanssens>
 * @package Component\Users
 */
class UsersTemplateHelperActivity extends Activities\TemplateHelperActivity
{
    public function message($config = array())
	{
	    $config = new Library\ObjectConfig($config);
		$config->append(array(
			'row'      => ''
		));
		
		$row = $config->row;

        if($row->name == 'session')
        {
		    $item = $this->getTemplate()->getView()->getRoute('option='.$row->type.'_'.$row->package.'&view=user&id='.$row->created_by);
		    
		    $message   = '<a href="'.$item.'">'.$row->title.'</a>'; 
		    $message  .= ' <span class="action">'.$row->status.'</span>';
		}
		else $message = parent::message($config);
		
		return $message;
	}
}