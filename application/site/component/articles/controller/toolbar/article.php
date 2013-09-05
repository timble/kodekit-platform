<?php
/**
 * Nooku Framework - http://www.nooku.org
 *
 * @copyright	Copyright (C) 2011 - 2013 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		git://git.assembla.com/nooku-framework.git for the canonical source repository
 */

use Nooku\Library;

/**
 * Article Controller Toolbar
 *
 * @author  Johan Janssens <http://nooku.assembla.com/profile/johanjanssens>
 * @package Component\Articles
 */
class ArticlesControllerToolbarArticle extends Library\ControllerToolbarActionbar
{
    protected function _initialize(Library\ObjectConfig $config)
    {
        $config->append(array('controller' => 'com:articles.controller.article'));

        parent::_initialize($config);
    }

    protected function _afterControlerBrowse(Library\CommandContext $context)
    {
        $controller = $this->getController();
        $view       = $controller->getView();

        if($view->getLayout() != 'form' && $controller->isEditable() && $controller->canEdit())
        {
            $article = $controller->getModel()->getRow();
            $route   = $controller->getView()->getTemplate()->getHelper('route')->article(
                array('row' => $article, 'layout' => 'form'
            ));

            $this->addCommand('edit', array('href'  => (string) $route));
        }
        else parent::_afterControlerBrowse($context);
    }
}