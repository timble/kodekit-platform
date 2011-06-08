<?php
/**
 * @version     $Id$
 * @category    Nooku
 * @package     Nooku_Server
 * @subpackage  Articles
 * @copyright   Copyright (C) 2011 Timble CVBA and Contributors. (http://www.timble.net).
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://www.nooku.org
 */

/**
 * Grid Template Helper
 *
 * @author      Gergo Erdosi <http://nooku.assembla.com/profile/gergoerdosi>
 * @category    Nooku
 * @package     Nooku_Server
 * @subpackage  Articles
 */

class ComArticlesTemplateHelperGrid extends KTemplateHelperGrid
{
    public function state($config = array())
    {
        $config = new KConfig($config);
        $config->append(array(
            'row' => null,
        ));

        switch($config->row->state)
        {
            case -1:
                $image = 'disabled.png';
                $alt   = JText::_('Archived');
                $text  = JText::_('Unarchive Item');
                $value = 0;

                break;

            case 0:
                $image = 'publish_x.png';
                $alt   = JText::_('Unpublished');
                $text  = JText::_('Publish Item');
                $value = 1;

                break;

            case 1:
                $now = gmdate('U');

                if($now <= strtotime($config->row->publish_up))
                {
                    $image = 'publish_y.png';
                    $alt   = JText::_('Published');
                }
                elseif($now <= strtotime($config->row->publish_down) || !(int) $config->row->publish_down)
                {
                    $image = 'publish_g.png';
                    $alt   = JText::_('Published');
                }
                else
                {
                    $image = 'publish_r.png';
                    $alt   = JText::_('Expired');
                }

                $text  = JText::_('Unpublish Item');
                $value = 0;

                break;
        }
 
        $data  = "{state:$value}";

		$html  = '<script src="media://lib_koowa/js/koowa.js" />';
        $html .= '<img src="media://system/images/'.$image.'" border="0" alt="'.$alt.'" data-action="edit" data-data="'.$data.'" />';
        
        return $html;
    }

    public function featured($config = array())
    {
        $config = new KConfig($config);
        $config->append(array(
            'row' => null,
        ));

        $image    = $config->row->featured ? 'enabled.png' : 'disabled.png';
        $alt 	  = $config->row->enabled ? JText::_( 'Featured' ) : JText::_( 'Unfeatured' );
        
        $featured = $config->row->featured ? 0 : 1;
     
        $data  = "{featured:$featured}";
        
        $html = '<script src="media://lib_koowa/js/koowa.js" />';
        $html .= '<img src="media://lib_koowa/images/'.$image.'" border="0" alt="'.$alt.'" data-action="edit" data-data="'.$data.'" />';

        return $html;
    }
}