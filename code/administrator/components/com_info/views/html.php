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
 * Default HTML View Class
 *
 * @author      John Bell <http://nooku.assembla.com/profile/johnbell>
 * @category     Nooku
 * @package     Nooku_Server
 * @subpackage  Info
 */
class ComInfoViewHtml extends ComDefaultViewHtml
{
    protected function _initialize(KConfig $config)
    {
        $config->append(array(
            'layout_default' => 'default',
            'auto_assign'    => false,
            'views'          => array(
                'system'         => JText::_('System Information'),
                'configuration'  => JText::_('Configuration File'),
                'directories'    => JText::_('Directory Permissions'),
                'phpinformation' => JText::_('PHP Information'),
                'phpsettings'    => JText::_('PHP Settings')
            )
        ));

        parent::_initialize($config);
    }

    public function display()
    {
        $this->getToolbar()->reset();

        return parent::display();
    }
}