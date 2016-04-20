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
 * Grid Template Helper
 *
 * @author  Tom Janssens <http://github.com/tomjanssens>
 * @package Kodekit\Platform\Attachments
 */
class TemplateHelperGrid extends Library\TemplateHelperAbstract
{
    public function thumbnails($config = array())
    {
        $config = new Library\ObjectConfig($config);
        $config->append(array(
            'attribs'   => array(
                'class'    => 'thumbnail',
                'align'    => 'right'
            ),
            'filter'   => array(
                'row'      => '',
                'table'    => '',
                'limit'    => '1'
            )
        ));

        $attribs = $this->buildAttributes($config->attribs);

        $controller = $this->getObject('com:articles.controller.attachment');
        $controller->getRequest()->setQuery($config->filter);

        $list = $controller->browse();

        $html = array();

        if(count($list))
        {
            foreach($list as $item)
            {
                if($item->file->isImage()) {
                    $html[] = '<img '.$attribs.' src="attachments://'.$item->thumbnail.'" />';
                }
            }

            return implode(' ', $html);
        }

        return false;
    }

    public function files($config = array())
    {
        $config = new Library\ObjectConfig($config);
        $config->append(array(
            'filter'   => array(
                'row'      => '',
                'table'    => '',
                'limit'    => ''
            )
        ));

        $controller = $this->getObject('com:articles.controller.attachment');
        $controller->getRequest()->setQuery($config->filter);

        $list = $controller->browse();

        $html = array();

        if(count($list))
        {
            $html[] = '<ul>';
            foreach($list as $item)
            {
                if(!$item->file->isImage())
                {
                    $html[] = '<li>';
                    $html[] = '<a href="#">';
                    $html[] = $item->name;
                    $html[] = '</a>';
                    $html[] = '</li>';
                }
            }

            $html[] = '</ul>';

            return implode(' ', $html);
        }

        return false;
    }
}