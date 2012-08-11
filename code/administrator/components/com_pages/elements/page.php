<?php
/**
 * @version     $Id: article.php 4368 2012-08-05 13:04:43Z gergoerdosi $
 * @package     Nooku_Server
 * @subpackage  Pages
 * @copyright   Copyright (C) 2011 - 2012 Timble CVBA and Contributors. (http://www.timble.net).
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://www.nooku.org
 */

/**
 * Page Element Class
 *
 * @author      Gergo Erdosi <http://nooku.assembla.com/profile/gergoerdosi>
 * @package     Nooku_Server
 * @subpackage  Pages
 */

class JElementPage extends JElement
{
    var $_name = 'Page';
    
    function fetchElement($name, $value, &$node, $control_name)
    {
        $listbox = KService::get('com://admin/pages.template.helper.listbox')
            ->pages(array('disable' => array('separator', 'url')));
        
        return $listbox;
    }
}