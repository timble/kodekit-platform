<?
/**
 * Nooku Platform - http://www.nooku.org/platform
 *
 * @copyright	Copyright (C) 2011 - 2014 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		https://github.com/nooku/nooku-platform for the canonical source repository
 */
?>

<h3><?= translate( 'Positions' ); ?></h3>
<ul class="navigation">
	<li>
        <a <? if(!state()->position && state()->application == 'site') echo 'class="active"' ?> href="<?= route('position=&application=site') ?>">
            <?= translate('All positions') ?>
        </a>
	</li>
	<? foreach($positions as $position) : ?>
	<li>
        <a <? if(state()->position == $position && state()->application == 'site') echo 'class="active"' ?> href="<?= route('sort=ordering&position='.$position.'&application=site') ?>">
            <?= $position; ?>
        </a>
	</li>
	<? endforeach ?>
</ul>