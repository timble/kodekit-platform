<?php
/**
 * Nooku Framework - http://www.nooku.org
 *
 * @copyright	Copyright (C) 2011 - 2013 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		http://github.com/nooku/nooku-platform for the canonical source repository
 */

return array(

    'identifiers' => array(

        'com:application.template.locator.component'  => array(
            'override_path' => APPLICATION_BASE.'/public/theme/bootstrap/templates/views'
        ),

        'com:application.template.locator.module'  => array(
            'override_path' => APPLICATION_BASE.'/public/theme/bootstrap/templates/modules'
        ),

        'com:application.template.filter.asset'  => array(
            'schemes' => array('assets://application/' => '/theme/bootstrap/')
        ),
    )
);
