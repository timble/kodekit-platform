<?php
/**
 * @version     $Id$
 * @category    Nooku
 * @package     Nooku_Server
 * @subpackage  Info
 * @copyright   Copyright (C) 2011 Timble CVBA and Contributors. (http://www.timble.net).
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://www.nooku.org
 */

/**
 * Template Helper
 *
 * @author      John Bell <http://nooku.assembla.com/profile/johnbell>
 * @category    Nooku
 * @package     Nooku_Server
 * @subpackage  Info
 */
class ComInfoTemplateHelperGrid extends KTemplateHelperGrid
{
    public function writable($config = array())
    {
        $config = new KConfig($config);

        $writable   = '<b><font color="green">'.JText::_('Writable').'</font></b>';
        $unwritable = '<b><font color="red">'.JText::_('Unwritable').'</font></b>';

        return $config->writable ? $writable : $unwritable;
    }
}