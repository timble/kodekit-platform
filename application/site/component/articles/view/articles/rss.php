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
 * Articles RSS View
 *
 * @author  Arunas Mazeika <http://github.com/amazeika>
 * @package Component\Articles
 */
class ArticlesViewArticlesRss extends Library\ViewRss
{
    protected function _fetchData(Library\ViewContext $context)
    {
        $context->data->category = $this->getCategory();

        parent::_fetchData($context);
    }

    public function getCategory()
    {
        //Get the category
        $category = $this->getObject('com:articles.model.categories')
                         ->table('articles')
                         ->id($this->getModel()->getState()->category)
                         ->fetch();

        $container = $this->getObject('com:files.model.containers')
            ->slug('attachments-attachments')
            ->fetch();

        //Set the category image
        if (isset( $category->image ) && !empty($category->image))
        {
            $path = JPATH_FILES.'/'.$container->path.'/'.$category->image;
            $size = getimagesize($path);

            $category->image = (object) array(
                'path'   => '/'.str_replace(APPLICATION_ROOT.DS, '', $path),
                'width'  => $size[0],
                'height' => $size[1]
            );
        }

        return $category;
    }
}