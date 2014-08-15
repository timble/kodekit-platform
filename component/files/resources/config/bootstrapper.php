<?php
/**
 * Nooku Platform - http://www.nooku.org/platform
 *
 * @copyright	Copyright (C) 2011 - 2013 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		https://github.com/nooku/nooku-platform for the canonical source repository
 */

use Nooku\Library;

return array(

    'priority' => Library\ObjectBootstrapper::PRIORITY_HIGH,

    'aliases'  => array(
        'com:files.model.entity.directories'  => 'com:files.model.entity.folders',
        'com:files.model.entity.directory'    => 'com:files.model.entity.folder',
    )

);