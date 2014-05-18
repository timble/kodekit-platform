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
 * Articles Module Html View
 *
 * @author  Arunas Mazeika <http://nooku.assembla.com/profile/arunasmazeika>
 * @package Component\Articles
 */
class ArticlesModuleArticlesHtml extends PagesModuleDefaultHtml
{
    /**
     * Renders the views output
     *
     * @return string
     */
    public function render()
    {
        // Preparing the sort and direction model states.
        switch ($this->module->params->get('sort_by', 'newest'))
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
        $category = str_replace(' ', '', $this->module->params->get('category', ''));
        if ($category) {
            $category = explode(',', $category);
        }

        // Prepare section state.
        $section = str_replace(' ', '', $this->module->params->get('section', ''));
        if ($section) {
            $section = explode(',', $section);
        }

        // Get access id.
        $user = $this->getObject('user');

        $articles = $this->getObject('com:articles.model.articles')
            ->set(array(
            'access'    => $user->isAuthentic(),
            'published' => 1,
            'limit'     => $this->module->params->get('count', 5),
            'sort'      => $sort,
            'direction' => $direction,
            'section'   => $section,
            'category'  => $category))
            ->getRowset();

        $this->articles   = $articles;
        $this->show_title = $this->module->params->get('show_title', false);

        // Set layout based on params.
        $this->setLayout($this->module->params->get('show_content', 0) ? 'articles' : 'links');

        return parent::render();
    }
}