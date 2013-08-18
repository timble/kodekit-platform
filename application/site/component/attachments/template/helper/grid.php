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
 * Grid Template Helper
 *
 * @author  Tom Janssens <http://nooku.assembla.com/profile/tomjanssens>
 * @package Component\Attachments
 */
class AttachmentsTemplateHelperGrid extends Library\TemplateHelperDefault
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

        $list = $this->getObject('com:attachments.controller.attachment', array(
			'request' => $this->getObject('lib:controller.request', array(
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
        $config = new Library\ObjectConfig($config);
        $config->append(array(
            'filter'   => array(
                'row'      => '',
                'table'    => '',
                'limit'    => ''
            )
        ));

        $list = $this->getObject('com:attachments.controller.attachment', array(
			'request' => $this->getObject('lib:controller.request', array(
				'query' => $config->filter
			))
		))->browse();
        
        $html = array();

        if(count($list))
        {
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