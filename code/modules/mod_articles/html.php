<?php
/**
 * @version        $Id$
 * @category       Nooku
 * @package        Nooku_Server
 * @subpackage     Articles
 * @copyright      Copyright (C) 2009 - 2012 Timble CVBA and Contributors. (http://www.timble.net)
 * @license        GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link           http://www.nooku.org
 */

/**
 * Articles module html view class.
 *
 * @author     Arunas Mazeika <http://nooku.assembla.com/profile/arunasmazeika>
 * @category   Nooku
 * @package    Nooku_Server
 * @subpackage Articles
 */

// TODO Prevent weird problem with autoloader which is unable to properly handle static calls to
// ComArticlesHelperRoute class when accessing an article view. Needs further investigation.
require_once JPATH_ROOT . '/components/com_articles/helpers/route.php';

class ModArticlesHtml extends ModDefaultHtml
{
    /**
     * Renders the views output
     *
     * @return string
     */
    public function display() {

        $this->assign('params', $this->params);

        // Preparing the sort and direction model states.
        switch ($this->params->get('sort_by', 'added_on')) {
            default:
            case 'newest':
                $sort      = 'created';
                $direction = 'DESC';
                break;
            case 'oldest':
                $sort      = 'created';
                $direction = 'ASC';
                break;
            case 'ordering':
                $sort      = 'ordering';
                $direction = 'ASC';
                break;
        }

        // Prepare category state.
        $category = str_replace(' ', '', $this->params->get('category', ''));
        if ($category) {
            $category = explode(',', $category);
        }

        // Prepare section state.
        $section = str_replace(' ', '', $this->params->get('section', ''));
        if ($section) {
            $section = explode(',', $section);
        }

        // Get access id.
        $user = JFactory::getUser();
        $aid  = $user->get('aid', 0);

        $articles = $this->getService('com://admin/articles.model.articles')
            ->set(array(
            'aid'       => $aid,
            'state'     => 1,
            'limit'     => $this->params->get('count', 5),
            'sort'      => $sort,
            'direction' => $direction,
            'section'   => $section,
            'category'  => $category,
            'featured'  => $this->params->get('show_featured', false)))
            ->getList();

        $this->assign('articles', $articles);

        // Set layout based on params.
        $this->setLayout($this->params->get('show_content', 0) ? 'articles' : 'links');

        return parent::display();
    }
}