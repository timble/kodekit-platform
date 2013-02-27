<?php
/**
 * @package        Nooku_Server
 * @subpackage     Attachments
 * @copyright      Copyright (C) 2009 - 2012 Timble CVBA and Contributors. (http://www.timble.net)
 * @license        GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link           http://www.nooku.org
 */

/**
 * Thumbnail Template Helper Class
 *
 * @author     Tom Janssens <http://nooku.assembla.com/profile/tomjanssens>
 * @package    Nooku_Server
 * @subpackage Attachments
 */
class ComAttachmentsTemplateHelperGrid extends KTemplateHelperDefault
{
    public function thumbnail($config = array())
    {
        $config = new KConfig($config);
        $config->append(array(
            'filter'   => array(
                'row'      => '',
                'table'    => '',
                'limit'    => '1'
            )
        ));

        $list = $this->getService('com://admin/attachments.controller.attachment', array(
			'request' => $this->getService('koowa:controller.request', array(
				'query' => $config->filter
			))
		))->browse();
        
        $html = array();
        
        if(count($list)) {
            $html[] = '<div class="pull-right">';
            foreach($list as $item) {
                if($item->file->isImage()) {
                    $html[] = '<a class="thumbnail" href="#">';
                    $html[] = '<img src="'.$item->thumbnail->thumbnail.'" />';
                    $html[] = '</a>';
                }
            }
            $html[] = '</div>';
    
            return implode(' ', $html);
        }
        
        return false;
    }
}