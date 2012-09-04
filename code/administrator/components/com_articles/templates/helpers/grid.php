<?php
/**
 * @version     $Id$
 * @package     Nooku_Server
 * @subpackage  Articles
 * @copyright   Copyright (C) 2011 - 2012 Timble CVBA and Contributors. (http://www.timble.net).
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://www.nooku.org
 */

/**
 * Grid Template Helper
 *
 * @author      Gergo Erdosi <http://nooku.assembla.com/profile/gergoerdosi>
 * @package     Nooku_Server
 * @subpackage  Articles
 */

class ComArticlesTemplateHelperGrid extends KTemplateHelperGrid
{
    public function order($config = array())
	{
		$config = new KConfig($config);
		
		if($config->featured == true) 
		{
		    $config->field = 'featured_ordering';
		    $config->data  = array('featured_order' => true);
		}
	    
	    return parent::order($config);
	}

    public function featured($config = array())
    {
        $config = new KConfig($config);
        $config->append(array(
            'row'   => null,
            'field' => 'featured'
        ))->append(array(
		    'data'	=> array($config->field => $config->row->{$config->field})
		));

        $image    = $config->row->featured ? 'enabled.png' : 'disabled.png';
        $alt 	  = $config->row->enabled ? JText::_( 'Featured' ) : JText::_( 'Unfeatured' );
       
        $config->data->{$config->field} =  $config->row->{$config->field} ? 0 : 1;
        $data = str_replace('"', '&quot;', $config->data);
        
        $html = '<script src="media://lib_koowa/js/koowa.js" />';
        $html .= '<img src="media://lib_koowa/images/'.$image.'" border="0" alt="'.$alt.'" data-action="edit" data-data="'.$data.'" />';

        return $html;
    }
}