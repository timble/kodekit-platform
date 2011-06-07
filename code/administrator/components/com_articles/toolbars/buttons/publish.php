<?php
/**
 * @version     $Id$
 * @category    Nooku
 * @package     Nooku_Server
 * @subpackage  Articles
 * @copyright   Copyright (C) 2011 Timble CVBA and Contributors. (http://www.timble.net).
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://www.nooku.org
 */

/**
 * Publish Toolbar Button Class
 *
 * @author      Gergo Erdosi <http://nooku.assembla.com/profile/gergoerdosi>
 * @category    Nooku
 * @package     Nooku_Server
 * @subpackage  Articles
 */

class ComArticlesToolbarButtonPublish extends ComDefaultToolbarButtonDefault
{
    protected function _initialize(KConfig $config)
    {
        $config->append(array(
            'text' => 'Publish',
            'attribs'  => array(
                'data-action' => 'edit',
                'data-data'   => '{state:1}'
            )
        ));

        parent::_initialize($config);
    }
}