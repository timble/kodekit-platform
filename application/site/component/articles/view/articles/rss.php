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
 * Articles RSS View
 *
 * @author  Arunas Mazeika <http://github.com/amazeika>
 * @package Kodekit\Platform\Articles
 */
class ViewArticlesRss extends Library\ViewRss
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
            $path = $container->getBasePath().'/'.$container->path.'/'.$category->image;
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