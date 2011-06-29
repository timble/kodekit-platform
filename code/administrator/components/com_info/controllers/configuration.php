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
 * Configuration Controller Class
 *
 * @author      John Bell <http://nooku.assembla.com/profile/johnbell>
 * @category    Nooku
 * @package     Nooku_Server
 * @subpackage  Info
 */

class ComInfoControllerConfiguration extends ComInfoControllerDefault
{
    protected function _initialize(KConfig $config)
    {
        $config->append(array(
            'model' => 'admin::com.info.model.configuration',
            'view'  => 'configuration'
        ));

        parent::_initialize($config);
    }
}