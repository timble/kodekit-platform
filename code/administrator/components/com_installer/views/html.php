<?php
/**
 * @version     $Id$
 * @category    Nooku
 * @package     Nooku_Server
 * @subpackage  Installer
 * @copyright   Copyright (C) 2011 Timble CVBA and Contributors. (http://www.timble.net).
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://www.nooku.org
 */

/**
 * Default HTML View Class
 *
 * @author      Stian Didriksen <http://nooku.assembla.com/profile/stiandidriksen>
 * @category    Nooku
 * @package     Nooku_Server
 * @subpackage  Installer
 */
class ComInstallerViewHtml extends ComDefaultViewHtml
{
    /**
     * Setting the default layout and the views in the submenu
     *
     * @return  void
     */
    protected function _initialize(KConfig $config)
    {
        $config->append(array(
            'layout' => 'default',
        ));

        parent::_initialize($config);
    }
}