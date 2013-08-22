<?
/**
 * Nooku Framework - http://www.nooku.org
 *
 * @copyright	Copyright (C) 2011 - 2013 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		git://git.assembla.com/nooku-framework.git for the canonical source repository
 */
?>

<h3><?= translate( 'Positions' ); ?></h3>
<ul class="navigation">
	<li>
        <a <? if(!$state->position && $state->application == 'site') echo 'class="active"' ?> href="<?= route('position=&application=site') ?>">
            <?= translate('All positions') ?>
        </a>
	</li>
	<? foreach(array_unique(object('com:pages.model.modules')->application('site')->getRowset()->position) as $position) : ?>
	<li>
        <a <? if($state->position == $position && $state->application == 'site') echo 'class="active"' ?> href="<?= route('sort=ordering&position='.$position.'&application=site') ?>">
            <?= $position; ?>
        </a>
	</li>
	<? endforeach ?>
</ul>