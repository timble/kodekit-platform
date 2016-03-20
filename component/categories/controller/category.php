<?php
/**
 * Kodekit Component - http://www.timble.net/kodekit
 *
 * @copyright      Copyright (C) 2011 - 2016 Timble CVBA and Contributors. (http://www.timble.net)
 * @license        MPL v2.0 <https://www.mozilla.org/en-US/MPL/2.0>
 * @link           https://github.com/timble/kodekit-categories
 */

namespace Kodekit\Component\Categories;

use Kodekit\Library;

/**
 * Category Controller
 *
 * @author  Johan Janssens <http://github.com/johanjanssens>
 * @package Kodekit\Component\Categories
 */
abstract class ControllerCategory extends Library\ControllerModel
{
    protected function _initialize(Library\ObjectConfig $config)
    {
        $config->append(array(
            'model' => 'com:categories.model.categories'
        ));

        parent::_initialize($config);
    }

    protected function _actionRender(Library\ControllerContext $context)
    {
        $view = $this->getView();

        //Alias the view layout
        if ($view instanceof Library\ViewTemplate)
        {
            $layout         = $view->getIdentifier()->toArray();
            $layout['name'] = $view->getLayout();
            unset($layout['path'][0]);

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
