<?php
/**
 * Nooku Platform - http://www.nooku.org/platform
 *
 * @copyright	Copyright (C) 2011 - 2014 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		https://github.com/nooku/nooku-platform for the canonical source repository
 */

namespace Nooku\Component\Languages;

use Nooku\Library;

/**
 * Languages Database Table
 *
 * @author  Gergo Erdosi <http://github.com/gergoerdosi>
 * @package Nooku\Component\Languages
 */
class DatabaseTableLanguages extends Library\DatabaseTableAbstract
{
    protected function _initialize(Library\ObjectConfig $config)
    {
        $config->append(array(
            'name'      => 'languages',
            'behaviors' => array(
                'sluggable' => array('columns' => array('name')), 'identifiable'
            ),
            'filters'   => array(
                'iso_code'  => array('com:languages.filter.iso'),
		    )
        ));

        parent::_initialize($config);
    }
}