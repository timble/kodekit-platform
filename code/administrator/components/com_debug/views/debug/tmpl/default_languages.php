<?php
/**
 * @version     $Id$
 * @category	Nooku
 * @package     Nooku_Components
 * @subpackage  Debug
 * @copyright   Copyright (C) 2011 Timble CVBA and Contributors. (http://www.timble.net).
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://www.nooku.org
 */
defined('KOOWA') or die( 'Restricted access' ); ?>

<h4><?= @text( 'Language Files Loaded' ) ?></h4>
<ul>
<? foreach ( KFactory::get('lib.joomla.language')->getPaths() as $extension => $files) : ?>
	<? foreach ( $files as $file => $status ) : ?>
		<li><?= $file ?></li>
	<? endforeach; ?>
<? endforeach; ?>
</ul>