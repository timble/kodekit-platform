<?php
/**
 * Nooku Platform - http://www.nooku.org/platform
 *
 * @copyright	Copyright (C) 2011 - 2013 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		https://github.com/nooku/nooku-platform for the canonical source repository
 */

return array(

    'aliases'  => array(
        'com:articles.model.categories'      => 'com:categories.model.categories',
        'com:articles.controller.attachment' => 'com:attachments.controller.attachment',
    ),

	'identifiers' => array(

		'com:articles.controller.article'  => array(
			'behaviors'  => array(
                'com:varnish.controller.behavior.cachable',
                'com:tags.controller.behavior.taggable'
            ),
		),
	)
);