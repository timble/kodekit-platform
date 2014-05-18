<?
/**
 * Nooku Framework - http://www.nooku.org
 *
 * @copyright	Copyright (C) 2011 - 2013 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		git://git.assembla.com/nooku-framework.git for the canonical source repository
 */
?>

<h3><?= translate('Applications') ?></h3>
<ul class="navigation">
	<? foreach($applications as $application) : ?>
	<li>
        <a <?= $state->application == $application ? 'class="active"' : '' ?> href="<?= route('application='.$application) ?>">
            <?= $application ?>
        </a>
	</li>
	<? endforeach ?>
</ul>