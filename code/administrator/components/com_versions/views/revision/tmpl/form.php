<?php
/**
 * @version     $Id$
 * @category	Nooku
 * @package     Nooku_Components
 * @subpackage  Versions
 * @copyright   Copyright (C) 2009 - 2012 Timble CVBA and Contributors. (http://www.timble.net).
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://www.nooku.org
 */

defined('KOOWA') or die( 'Restricted access' ); ?>

<pre>
    <? print_r(json_decode($revision->data, true)); ?>
</pre>