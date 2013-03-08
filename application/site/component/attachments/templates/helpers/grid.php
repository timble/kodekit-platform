<?php
/**
 * @package        Nooku_Server
 * @subpackage     Attachments
 * @copyright      Copyright (C) 2009 - 2012 Timble CVBA and Contributors. (http://www.timble.net)
 * @license        GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link           http://www.nooku.org
 */

use Nooku\Framework;

/**
 * Thumbnail Template Helper Class
 *
 * @author     Tom Janssens <http://nooku.assembla.com/profile/tomjanssens>
 * @package    Nooku_Server
 * @subpackage Attachments
 */
class ComAttachmentsTemplateHelperGrid extends Framework\TemplateHelperDefault
{
    public function thumbnails($config = array())
    {
        $config = new Framework\Config($config);
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
        
        $attribs = $this->_buildAttributes($config->attribs);

        $list = $this->getService('com://admin/attachments.controller.attachment', array(
			'request' => $this->getService('lib://nooku/controller.request', array(
				'query' => $config->filter
			))
		))->browse();
        
        $html = array();
        
        if(count($list)) {
            foreach($list as $item) {
                if($item->file->isImage()) {
                    $html[] = '<img '.$attribs.' src="'.$item->thumbnail->thumbnail.'" />';
                }
            }
    
            return implode(' ', $html);
        }
        
        return false;
    }
    
    public function files($config = array())
    {
        $config = new Framework\Config($config);
        $config->append(array(
            'filter'   => array(
                'row'      => '',
                'table'    => '',
                'limit'    => ''
            )
        ));

        $list = $this->getService('com://admin/attachments.controller.attachment', array(
			'request' => $this->getService('lib://nooku/controller.request', array(
				'query' => $config->filter
			))
		))->browse();
        
        $html = array();

        if(count($list)) {
            $html[] = '<ul>';
            foreach($list as $item) {
                if(!$item->file->isImage()) {
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