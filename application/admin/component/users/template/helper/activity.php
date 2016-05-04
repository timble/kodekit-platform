<?php
/**
 * Kodekit Platform - http://www.timble.net/kodekit
 *
 * @copyright	Copyright (C) 2011 - 2016 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license		MPL v2.0 <https://www.mozilla.org/en-US/MPL/2.0>
 * @link		https://github.com/timble/kodekit-platform for the canonical source repository
 */

namespace Kodekit\Platform\Users;

use Kodekit\Library;
use Kodekit\Component\Activities;

/**
 * Activity Template Helper
 *
 * @author  Johan Janssens <http://github.com/johanjanssens>
 * @package Kodekit\Platform\Users
 */
class TemplateHelperActivity extends Activities\TemplateHelperActivity
{
    public function message($config = array())
    {
        $config = new Library\ObjectConfig($config);
        $config->append(array(
            'entity' => ''
        ));

        $entity = $config->entity;

        if($entity->name == 'session')
        {
            $query = array(
                'component' => $entity->type.'_'.$entity->package,
                'view'      => 'user',
                'id'        => $entity->created_by
            );

            $url = $this->getObject('lib:dispatcher.router.route', array('escape' =>  true))
                ->setQuery($query);

            $message   = '<a href="'.$url.'">'.$entity->title.'</a>';
            $message  .= ' <span class="action">'.$entity->status.'</span>';
        }
        else $message = parent::message($config);

        return $message;
    }
}