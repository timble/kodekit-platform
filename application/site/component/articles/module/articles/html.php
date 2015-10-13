<?php
/**
 * Nooku Platform - http://www.nooku.org/platform
 *
 * @copyright	Copyright (C) 2011 - 2014 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		https://github.com/nooku/nooku-platform for the canonical source repository
 */

use Nooku\Library;
use Nooku\Component\Pages;

/**
 * Articles Module Html View
 *
 * @author  Arunas Mazeika <http://github.com/amazeika>
 * @package Component\Articles
 */
class ArticlesModuleArticlesHtml extends Pages\ModuleAbstract
{
    /**
     * Renders and echo's the views output
     *
     * @return ArticlesModuleArticlesHtml
     */
    protected function _actionRender(Library\ViewContext $context)
    {
        //Force layout type to 'mod' to force using the module locator for partial layouts
        $layout = $context->layout;

        if (is_string($layout) && strpos($layout, '.') === false)
        {
            $identifier = $this->getIdentifier()->toArray();
            $identifier['type'] = 'mod';
            $identifier['name'] = $layout;
            unset($identifier['path'][0]);

            $context->layout = $this->getIdentifier($identifier);
        }

        // Preparing the sort and direction model states.
        $params = $context->data->module->getParameters();

        switch ($params->get('sort_by', 'newest'))
        {
            default:
            case 'newest':
                $sort      = 'created_on';
                $direction = 'DESC';
                break;
            case 'oldest':
                $sort      = 'created_on';
                $direction = 'ASC';
                break;
            case 'ordering':
                $sort      = 'ordering';
                $direction = 'ASC';
                break;
        }

        // Prepare category state.
        $category = str_replace(' ', '', $params->get('category', ''));
        if ($category) {
            $category = explode(',', $category);
        }

        //Get user
        $user = $this->getObject('user');

        //Render the articles through the article controller
        $result = $this->getObject('com:articles.controller.article')
            ->access($user->isAuthentic())
            ->published(1)
            ->limit($params->get('count', 5))
            ->sort($sort)
            ->direction($direction)
            ->category($category)
            ->layout($context->layout)
            ->render();

        return $result;
    }
}