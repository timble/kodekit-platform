<?
/**
 * Kodekit Platform - http://www.timble.net/kodekit
 *
 * @copyright	Copyright (C) 2011 - 2016 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license		MPL v2.0 <https://www.mozilla.org/en-US/MPL/2.0>
 * @link		https://github.com/timble/kodekit-platform for the canonical source repository
 */
?>

<h3><?= translate( 'Positions' ); ?></h3>
<ul class="navigation">
	<li>
        <a <? if(!parameters()->position && parameters()->application == 'site') echo 'class="active"' ?> href="<?= route('position=&application=site') ?>">
            <?= translate('All positions') ?>
        </a>
	</li>
	<? foreach($positions as $position) : ?>
	<li>
        <a <? if(parameters()->position == $position && parameters()->application == 'site') echo 'class="active"' ?> href="<?= route('sort=ordering&position='.$position.'&application=site') ?>">
            <?= $position; ?>
        </a>
	</li>
	<? endforeach ?>
</ul>