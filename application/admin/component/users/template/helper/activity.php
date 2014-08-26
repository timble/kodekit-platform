<?php
/**
 * Nooku Platform - http://www.nooku.org/platform
 *
 * @copyright	Copyright (C) 2011 - 2014 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		http://github.com/nooku/nooku-platform for the canonical source repository
 */

use Nooku\Library;
use Nooku\Component\Activities;

/**
 * Activity Template Helper
 *
 * @author  Johan Janssens <http://github.com/johanjanssens>
 * @package Component\Users
 */
class UsersTemplateHelperActivity extends Activities\TemplateHelperActivity
{
    public function message($config = array())
	{
	    $config = new Library\ObjectConfig($config);
		$config->append(array(
			'entity'      => ''
		));
		
		$entity = $config->entity;

        if($entity->name == 'session')
        {
		    $item = $this->getTemplate()->route('component='.$entity->type.'_'.$entity->package.'&view=user&id='.$entity->created_by);
		    
		    $message   = '<a href="'.$item.'">'.$entity->title.'</a>';
		    $message  .= ' <span class="action">'.$entity->status.'</span>';
		}
		else $message = parent::message($config);
		
		return $message;
	}
}