<?
/**
 * @category	Nooku
 * @package     Nooku_Server
 * @subpackage  Cache
 * @copyright   Copyright (C) 2011 - 2012 Timble CVBA and Contributors. (http://www.timble.net).
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://www.nooku.org
 */
?>

<h3><?= @text( 'Groups' ); ?></h3>
<nav>
    <a <? if(!$state->group) echo 'class="active"' ?> href="<?= @route('group=') ?>">
        <?= @text('All groups') ?>
    </a>
    <? foreach($groups as $group) : ?>
    <a <? if($state->group == $group->name) echo 'class="active"' ?> href="<?= @route('group='.$group->name) ?>">
        <?= $group->name; ?>
    </a>
    <? endforeach ?>
</nav>
<h3><?= @text( 'Details' ); ?></h3>
<p><?= @text('Files').':'.$count ?></p>
<p><?= @text('Size').':'.number_format($size / 1024, 2) ?></p>