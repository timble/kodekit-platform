<?
/**
 * Nooku Platform - http://www.nooku.org/platform
 *
 * @copyright	Copyright (C) 2011 - 2014 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		https://github.com/nooku/nooku-platform for the canonical source repository
 */
?>

<h3><?= translate('Applications') ?></h3>
<ul class="navigation">
	<? foreach($applications as $application) : ?>
	<li>
        <a <?= state()->application == $application ? 'class="active"' : '' ?> href="<?= route('application='.$application) ?>">
            <?= $application ?>
        </a>
	</li>
	<? endforeach ?>
</ul>