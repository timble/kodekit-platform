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
 * Template Database Rowset Class
 *
 * @author      Stian Didriksen <http://nooku.assembla.com/profile/stiandidriksen>
 * @category    Nooku
 * @package     Nooku_Server
 * @subpackage  Templates
 */
class ComTemplatesDatabaseRowsetTemplates extends KDatabaseRowsetAbstract
{       
    protected function _initialize(KConfig $config)
    {
        $config->append(array(
            'identity_column'   => 'name'
        ));
        
        parent::_initialize($config);
    }
}