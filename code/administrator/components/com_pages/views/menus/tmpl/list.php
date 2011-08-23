<?php
/**
 * @version     $Id$
 * @category    Nooku
 * @package     Nooku_Server
 * @subpackage  Pages
 * @copyright   Copyright (C) 2011 Timble CVBA and Contributors. (http://www.timble.net).
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://www.nooku.org
 */

defined('KOOWA') or die('Restricted access') ?>

<ul class="list">
	<? foreach ($menus as $menu) : ?>
	<li>
		<a class="<?= $state->menu == $menu->id ? 'active' : ''; ?>" href="<?= @route('view=pages&menu='.$menu->id ) ?>">
			<?= @escape($menu->title) ?> (<?= $menu->page_count; ?>)
		</a>
	</li>
	<? endforeach ?>
</ul>