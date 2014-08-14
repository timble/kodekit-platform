<?php
/**
 * Nooku Platform - http://www.nooku.org/platform
 *
 * @copyright      Copyright (C) 2011 - 2014 Timble CVBA and Contributors. (http://www.timble.net)
 * @license        GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link           https://github.com/nooku/nooku-platform
 */

namespace Nooku\Component\Categories;

use Nooku\Library;

/**
 * Category Controller
 *
 * @author  Johan Janssens <http://github.com/johanjanssens>
 * @package Nooku\Component\Categories
 */
abstract class ControllerCategory extends Library\ControllerModel
{
    protected function _initialize(Library\ObjectConfig $config)
    {
        $config->append(array(
            'model'     => 'com:categories.model.categories',
            'behaviors' => array('com:attachments.controller.behavior.attachable'),
        ));

        parent::_initialize($config);
    }

    protected function _actionRender(Library\ControllerContext $context)
    {
        $view = $this->getView();

        //Alias the view layout
        if ($view instanceof Library\ViewTemplate) {
            $layout         = $view->getIdentifier()->toArray();
            $layout['name'] = $view->getLayout();

            $alias            = $layout;
            $alias['package'] = 'categories';

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
