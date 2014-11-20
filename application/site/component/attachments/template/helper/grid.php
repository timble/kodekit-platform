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
 * Grid Template Helper
 *
 * @author  Tom Janssens <http://github.com/tomjanssens>
 * @package Component\Attachments
 */
class AttachmentsTemplateHelperGrid extends Library\TemplateHelperAbstract
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

        $controller = $this->getObject('com:attachments.controller.attachment');
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

        $controller = $this->getObject('com:attachments.controller.attachment');
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