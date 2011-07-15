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
 * System Model Class
 *
 * @author      John Bell <http://nooku.assembla.com/profile/johnbell>
 * @category    Nooku
 * @package     Nooku_Server
 * @subpackage  Info
 */

class ComInfoModelSystem extends KModelAbstract
{
    public function getList()
    {
        if(!$this->_list)
        {
            $nooku_version   = new JVersion();
            $server_software = $_SERVER['SERVER_SOFTWARE'] ? $_SERVER['SERVER_SOFTWARE'] : JText::_('n/a');

            $rows = array(
                array(
                    'setting' => JText::_('PHP Built On'),
                    'value'   => php_uname()
                ),
                array(
                    'setting' => JText::_('Database Version'),
                    'value'   => mysqli_get_server_info(KFactory::get('lib.koowa.database.adapter.mysqli')->getConnection())
                ),
                array(
                    'setting' => JText::_('Database Collation'),
                    'value'   => KFactory::tmp('admin::com.extensions.database.table.plugins')->getSchema()->collation
                ),
                array(
                    'setting' => JText::_('PHP Version'),
                    'value'   => phpversion()
                ),
                array(
                    'setting' => JText::_('Web Server'),
                    'value'   => $server_software
                ),
                array(
                    'setting' => JText::_('WebServer to PHP Interface'),
                    'value'   => php_sapi_name()
                ),
                array(
                    'setting' => JText::_('Nooku Server Version'),
                    'value'   => $nooku_version->getLongVersion()
                ),
                array(
                    'setting' => JText::_('User Agent'),
                    'value'   => $_SERVER['HTTP_USER_AGENT'], ENT_QUOTES
                )
            );

            $this->_list = KFactory::tmp('admin::com.info.database.rowset.system')
                ->addData($rows, false);
        }

        return $this->_list;
    }
}