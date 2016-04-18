<?php
/**
 * Kodekit Platform - http://www.timble.net/kodekit
 *
 * @copyright	Copyright (C) 2011 - 2013 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license		MPL v2.0 <https://www.mozilla.org/en-US/MPL/2.0>
 * @link		https://github.com/timble/kodekit-platform for the canonical source repository
 */

$theme_path = APPLICATION_BASE.'/public/theme/bootstrap';

return array(

    'identifiers' => array(

        'dispatcher'  => array(
            'behaviors' => 'com:pages.dispatcher.behavior.accessible'
        ),

        'com:pages.controller.module'  => array(
            'behaviors'  => array('com:varnish.controller.behavior.cachable'),
        ),

        'com:pages.controller.window'  => array(
            'behaviors'  => array('com:varnish.controller.behavior.cachable'),
        ),

        'com:pages.template.locator.module'  => array(
            'path_templates' => array($theme_path.'/templates/modules')
        ),

        'com:pages.template.filter.asset'  => array(
            'schemes' => array(
                'assets://theme/' => '/theme/bootstrap/'
            )
        ),

        'lib:template.locator.component'  => array(
            'path_templates' => array($theme_path.'/templates/view/<Package>/<Path>/<File>.<Format>.<Type>')
        ),
    )
);

