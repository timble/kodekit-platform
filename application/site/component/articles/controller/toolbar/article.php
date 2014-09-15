<?php
/**
 * Nooku Platform - http://www.nooku.org/platform
 *
 * @copyright	Copyright (C) 2011 - 2014 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		https://github.com/nooku/nooku-platform for the canonical source repository
 */

use Nooku\Library;

/**
 * Article Controller Toolbar
 *
 * @author  Johan Janssens <http://github.com/johanjanssens>
 * @package Component\Articles
 */
class ArticlesControllerToolbarArticle extends Library\ControllerToolbarActionbar
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
            $route   = $controller->getView()->getTemplate()->createHelper('route')-article(
                array('entity' => $article, 'layout' => 'form')
            );

            $this->addCommand('edit', array('href'  => (string) $route));
        }
        else parent::_afterRead($context);
    }
}