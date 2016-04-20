<?php
/**
 * Kodekit Platform - http://www.timble.net/kodekit
 *
 * @copyright	Copyright (C) 2011 - 2013 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license		MPL v2.0 <https://www.mozilla.org/en-US/MPL/2.0>
 * @link		https://github.com/timble/kodekit-platform for the canonical source repository
 */

return array(

    'identifiers' => array(

        'com:pages.view.window'         => array(
            'template_filters' => array(
                'com:files.template.filter.files',
                'com:attachments.template.filter.attachments'
            )
        ),

    )
);

