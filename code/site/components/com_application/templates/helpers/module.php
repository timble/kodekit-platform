<?php
/**
 * @version     $Id: listbox.php 3031 2011-10-09 14:21:07Z johanjanssens $
 * @package     Nooku_Server
 * @subpackage  Pages
 * @copyright   Copyright (C) 2011 Timble CVBA and Contributors. (http://www.timble.net).
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://www.nooku.org
 */

/**
 * Module Template Helper Class
 *
 * @author      Johan Janssens <http://nooku.assembla.com/profile/johanjanssens>
 * @package     Nooku_Server
 * @subpackage  Application
 */

class ComApplicationTemplateHelperModule extends ComPagesTemplateHelperModule
{
    protected function _initialize(KConfig $config)
    {
        $config->append(array(
            'modules' => 'application.modules',
        ));

        parent::_initialize($config);
    }
}