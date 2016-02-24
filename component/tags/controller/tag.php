<?php
/**
 * Nooku Platform - http://www.nooku.org/platform
 *
 * @copyright	Copyright (C) 2011 - 2014 Timble CVBA and Contributors. (http://www.timble.net)
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		https://github.com/nooku/nooku-platform
 */

namespace Nooku\Component\Tags;

use Nooku\Library;

/**
 * Tag Controller
 *
 * @author  Johan Janssens <http://github.com/johanjanssens>
 * @package Nooku\Component\Tags
 */
class ControllerTag extends Library\ControllerModel
{
    protected function _initialize(Library\ObjectConfig $config)
    {
        $config->append(array(
            'model' => 'com:tags.model.tags'
        ));

        //Alias the permission
        $permission         = $this->getIdentifier()->toArray();
        $permission['path'] = array('controller', 'permission');

        $this->getObject('manager')->registerAlias('com:tags.controller.permission.tag', $permission);

        parent::_initialize($config);
    }

    public function getModel()
    {
        if(!$this->_model instanceof Library\ModelInterface)
        {
            $package = $this->getIdentifier()->package;
            $this->_model = $this->getObject($this->_model, array('table' => $package.'_tags'));

            //Inject the request into the model state
            $this->_model->setState($this->getRequest()->query->toArray());
        }

        return $this->_model;
    }

    protected function _actionRender(Library\ControllerContext $context)
    {
        $view = $this->getView();

        if($view instanceof Library\ViewTemplate)
        {
            $layout         = $view->getIdentifier()->toArray();
            $layout['name'] = $view->getLayout();
            unset($layout['path'][0]);

            $alias            = $layout;
            $alias['package'] = 'tags';

            $this->getObject('manager')->registerAlias($alias, $layout);
        }

        return parent::_actionRender($context);
    }
}
