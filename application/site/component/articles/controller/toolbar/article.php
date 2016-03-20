<?php
/**
 * Kodekit Platform - http://www.timble.net/kodekit
 *
 * @copyright	Copyright (C) 2011 - 2016 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license		MPL v2.0 <https://www.mozilla.org/en-US/MPL/2.0>
 * @link		https://github.com/timble/kodekit-platform for the canonical source repository
 */

namespace Kodekit\Platform\Articles;

use Kodekit\Library;

/**
 * Article Controller Toolbar
 *
 * @author  Johan Janssens <http://github.com/johanjanssens>
 * @package CKodekit\Platform\Articles
 */
class ControllerToolbarArticle extends Library\ControllerToolbarActionbar
{
    protected function _initialize(Library\ObjectConfig $config)
    {
        $config->append(array('controller' => 'com:articles.controller.article'));

        parent::_initialize($config);
    }

    protected function _afterRead(Library\ControllerContextInterface $context)
    {
        $controller = $this->getController();
        $view       = $controller->getView();

        if($view->getLayout() != 'form' && $controller->isEditable() && $controller->canEdit())
        {
            $article = $controller->getModel()->fetch();
            $route   = $controller->getView()->getTemplate()->createHelper('route')->article(
                array('entity' => $article, 'layout' => 'form')
            );

            $this->addCommand('edit', array('href'  => (string) $route));
        }
        else parent::_afterRead($context);
    }
}