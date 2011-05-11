<?php
/**
 * @version     $Id$
 * @category    Nooku
 * @package     Nooku_Server
 * @subpackage  Templates
 * @copyright   Copyright (C) 2011 Timble CVBA and Contributors. (http://www.timble.net).
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://www.nooku.org
 */

/**
 * Template Preview Toolbar Button class
 *
 * @author      Stian Didriksen <http://nooku.assembla.com/profile/stiandidriksen>
 * @category    Nooku
 * @package     Nooku_Server
 * @subpackage  Templates    
 */
class ComTemplatesToolbarButtonPreview extends KToolbarButtonGet
{
    /**
     * Opens up a popup window that exposes the module positions in a template
     *
     * @return  string
     */
    public function getOnClick()
    {
        $url      = KRequest::get('get.client', 'int', 0) ? JURI::base() : JURI::root();
        $url      = json_encode($url.'index.php?tp=1&amp;template='.KRequest::get('get.name', 'cmd'));

        return str_replace('"', '&quot;', "window.open($url, '_blank', 'location=no,toolbar=no');return false;");
    }
}