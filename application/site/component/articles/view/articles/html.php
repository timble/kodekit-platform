<?php
/**
 * Kodekit Platform - http://www.timble.net/kodekit
 *
 * @copyright      Copyright (C) 2011 - 2016 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license        MPL v2.0 <https://www.mozilla.org/en-US/MPL/2.0>
 * @link           https://github.com/timble/kodekit-platform for the canonical source repository
 */

namespace Kodekit\Platform\Articles;

use Kodekit\Library;

/**
 * Articles Html View
 *
 * @author  Arunas Mazeika <http://github.com/amazeika>
 * @package Kodekit\Platform\Articles
 */
class ViewArticlesHtml extends ViewHtml
{
    protected function _initialize(Library\ObjectConfig $config)
    {
        $config->append(array(
            'template_functions' => array(
                'highlight'     => array($this, 'highlight'),
            ),
        ));

        parent::_initialize($config);
    }

    protected function _fetchData(Library\ViewContext $context)
    {
        //Get the parameters
        $params = $this->getObject('pages')->getActive()->getParams('page');

        //Get the category
        $category = $this->getCategory();

        //Set the pathway
        $page    = $this->getObject('pages')->getActive();
        $pathway = $this->getObject('pages')->getPathway();
        if ($page->getLink()->query['view'] == 'categories') {
            $pathway[] = array('title' => $page->getLink());
        }

        $context->data->params   = $params;
        $context->data->category = $category;

        parent::_fetchData($context);
    }

    public function getCategory()
    {
        $category = $this->getObject('com:articles.model.categories')
            ->table('articles')
            ->id($this->getModel()->getState()->category)
            ->fetch();

        return $category;
    }

    public function highlight($text)
    {
        if ($search = $this->getModel()->getState()->search) {
            $text = preg_replace('/' . $search . '(?![^<]*?>)/i', '<span class="highlight">' . $search . '</span>', $text);
        }

        return $text;
    }
}