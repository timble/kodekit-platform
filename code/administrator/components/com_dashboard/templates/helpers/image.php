<?php
/**
 * @version     $Id: behavior.php 2382 2011-07-22 18:37:26Z gnomeontherun $
 * @category    Nooku
 * @package     Nooku_Server
 * @subpackage  Dashboard
 * @copyright   Copyright (C) 2011 Timble CVBA and Contributors. (http://www.timble.net).
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://www.nooku.org
 */

/**
 * Icon Template Helper
 *
 * @author      Johan Janssens <http://nooku.assembla.com/profile/johanjanssens>
 * @category    Nooku
 * @package     Nooku_Server
 * @subpackage  Dashboard
 */
class ComDashboardTemplateHelperImage extends KTemplateHelperAbstract
{
    public function icon($config = array())
    {
	     $config = new KConfig($config);
         $config->append(array(
             'link'  => 'order',
             'text'  => '',
             'image' => '',
             'style' => KFactory::get('joomla:language')->isRTL() ? 'right' : 'left'
        ));
         
		$html  = '<div style="float:'.$config->style.'">';
		$html .= '<div class="icon">';
		$html .= '	<a href='.JRoute::_('index.php?'.$config->link).'>';
		$html .= '		<img src="base://templates/default/images/header/'.$config->image.'.png" />';		    
		$html .= '		<span>'.JText::_($config->text).'</span>';
		$html .= '	</a>';
		$html .= '</div>';
		$html .= '</div>';
		
		return $html;
    }
}