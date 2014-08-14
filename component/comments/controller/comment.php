<?php
/**
 * Nooku Platform - http://www.nooku.org/platform
 *
 * @copyright      Copyright (C) 2011 - 2014 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license        GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link           https://github.com/nooku/nooku-platform for the canonical source repository
 */

namespace Nooku\Component\Comments;

use Nooku\Library;

/**
 * Comment Controller
 *
 * @author        Johan Janssens <http://github.com/johanjanssens>
 * @package       Nooku\Component\Comments
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

    protected function _actionRender(Library\ControllerContextInterface $context)
    {
        $view = $this->getView();

        //Alias the view layout
        if ($view instanceof Library\ViewTemplate) {
            $layout         = $view->getIdentifier()->toArray();
            $layout['name'] = $view->getLayout();

            $alias            = $layout;
            $alias['package'] = 'comments';

            $this->getObject('manager')->registerAlias($alias, $layout);
        }

        return parent::_actionRender($context);
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