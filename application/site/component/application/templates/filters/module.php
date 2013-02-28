<?php
/**
 * @package     Nooku_Server
 * @subpackage  Application
 * @copyright   Copyright (C) 2007 - 2012 Johan Janssens. All rights reserved.
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://www.nooku.org
 */

/**
 * Module Template Filter Class
 *
 * @author    	Johan Janssens <http://nooku.assembla.com/profile/johanjanssens>
 * @package     Nooku_Server
 * @subpackage  Application
 */
class ComApplicationTemplateFilterModule extends ComPagesTemplateFilterModule
{
    protected function _initialize(KConfig $config)
    {
        $config->append(array(
            'modules' => 'application.modules',
        ));

        parent::_initialize($config);
    }
}