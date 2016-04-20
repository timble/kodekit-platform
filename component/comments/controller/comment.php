<?php
/**
 * Kodekit Component - http://www.timble.net/kodekit
 *
 * @copyright      Copyright (C) 2011 - 2016 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license        MPL v2.0 <https://www.mozilla.org/en-US/MPL/2.0>
 * @link           https://github.com/timble/kodekit-comments for the canonical source repository
 */

namespace Kodekit\Component\Comments;

use Kodekit\Library;

/**
 * Comment Controller
 *
 * @author        Johan Janssens <http://github.com/johanjanssens>
 * @package       Kodekit\Component\Comments
 */
abstract class ControllerComment extends Library\ControllerModel
{
    protected function _initialize(Library\ObjectConfig $config)
    {
        $config->append(array(
            'model' => 'com:comments.model.comments'
        ));

        //Alias the permission
        $permission         = $this->getIdentifier()->toArray();
        $permission['path'] = array('controller', 'permission');

        $this->getObject('manager')->registerAlias('com:comments.controller.permission.comment', $permission);

        parent::_initialize($config);
    }

    public function getRequest()
    {
        $request = parent::getRequest();

        //Force set the 'table' in the request
        $request->query->table = $this->getIdentifier()->package;
        $request->data->table  = $this->getIdentifier()->package;

        return $request;
    }
}