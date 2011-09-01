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
 * Phpsettings Model Class
 *
 * @author      John Bell <http://nooku.assembla.com/profile/johnbell>
 * @category    Nooku
 * @package     Nooku_Server
 * @subpackage  Info
 */
class ComInfoModelPhpsettings extends KModelAbstract
{
    public function getList()
    {
        if(!$this->_list)
        {
            $rows = array(
                array(
                    'setting' => JText::_('Safe Mode'),
                    'value'   => (ini_get('safe_mode') == '1') ? JText::_('ON') : JText::_('OFF')
                ),
                array(
                    'setting' => JText::_('Open basedir'),
                    'value'   => (($open_basedir = ini_get('open_basedir')) ? $open_basedir : JText::_('none') )
                ),
                array(
                    'setting' => JText::_('Display Errors'),
                    'value'   => (ini_get('display_errors') == '1') ? JText::_('ON') : JText::_('OFF')
                ),
                array(
                    'setting' => JText::_('Short Open Tags'),
                    'value'   => (ini_get('short_open_tag') == '1') ? JText::_('ON') : JText::_('OFF')
                ),
                array(
                    'setting' => JText::_('File Uploads'),
                    'value'   => (ini_get('file_uploads') == '1') ? JText::_('ON') : JText::_('OFF')
                ),
                array(
                    'setting' => JText::_('Magic Quotes'),
                    'value'   => (ini_get('magic_quotes_gpc') == '1') ? JText::_('ON') : JText::_('OFF')
                ),
                array(
                    'setting' => JText::_('Register Globals'),
                    'value'   => (ini_get('register_globals') == '1') ? JText::_('ON') : JText::_('OFF')
                ),
                array(
                    'setting' => JText::_('Output Buffering'),
                    'value'   => (ini_get('output_buffering') == '1') ? JText::_('ON') : JText::_('OFF')
                ),
                array(
                    'setting' => JText::_('Session Save Path'),
                    'value'   => (($save_path = ini_get('session.save_path')) ? $save_path : JText::_('none'))
                ),
                array(
                    'setting' => JText::_('Session Auto Start'),
                    'value'   => (int) ini_get('session.auto_start')
                ),
                array(
                    'setting' => JText::_('XML Enabled'),
                    'value'   => extension_loaded('xml') ? JText::_('Yes') : JText::_('No')
                ),
                array(
                    'setting' => JText::_('Zlib Enabled'),
                    'value'   => extension_loaded('zlib') ? JText::_('Yes') : JText::_('No')
                ),
                array(
                    'setting' => JText::_('Disabled Functions'),
                    'value'   => (($disabled_functions = ini_get('disable_functions')) ? $disabled_functions : JText::_('none'))
                ),
                array(
                    'setting' => JText::_('Mbstring Enabled'),
                    'value'   => extension_loaded('mbstring') ? JText::_('Yes') : JText::_('No')
                ),
                array(
                    'setting' => JText::_('Iconv Available'),
                    'value'   => function_exists('iconv') ? JText::_('Yes') : JText::_('No')
                )
            );

            $this->_list = KFactory::get('com://admin/info.database.rowset.system')
                ->addData($rows, false);
        }

        return $this->_list;
    }
}