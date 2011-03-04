<?php
/**
 * @version     $Id$
 * @category	Nooku
 * @package     Nooku_Server
 * @subpackage  Sections
 * @copyright   Copyright (C) 2011 Timble CVBA and Contributors. (http://www.timble.net).
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://www.nooku.org
 */

/**
 * Sections Template Grid Helper Class
 *
 * @author     	John Bell <http://nooku.assembla.com/profile/johnbell>
 * @category	Nooku
 * @package     Nooku_Server
 * @subpackage  Sections
 * @see        	http://ajaxpatterns.org/Data_Grid
 */
class ComSectionsTemplateHelperGrid extends KTemplateHelperGrid
{
    /**
  	 * Render an enable field
   	 *
   	 * @param       array   An optional array with configuration options
   	 * @return      string  Html
   	 */
   	public function publish($config = array())
   	{
    	$config = new KConfig($config);
       	$config->append(array(
        	'row'           => null,
      	));

      	$html = '';
       	$html .= '<script src="media://lib_koowa/js/koowa.js" />';
                
      	$img    = $config->row->enabled ? 'enabled.png' : 'disabled.png';
       	$alt    = $config->row->enabled ? JText::_( 'Published' ) : JText::_( 'Draft' );
       	$text   = $config->row->enabled ? JText::_( 'Unpublish Item' ) : JText::_( 'Publish Item' );
       	$value  = $config->row->enabled ? 0 : 1;

       	$url   = $this->_createURL($config->row).'&id='.$config->row->id;
      	$token = JUtility::getToken();

     	$rel   = "{method:'post', url:'$url', params:{enabled:$value, _token:'$token', action:'edit'}}";
      	$html .= '<img src="media://lib_koowa/images/'. $img .'" border="0" alt="'. $alt .'" class="submitable" rel="'.$rel.'" />';

       	return $html;
 	}
}