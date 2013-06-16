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
<ul class="navigation">
    <li>
        <a <? if(!$state->group) echo 'class="active"' ?> href="<?= @route('group=') ?>">
            <?= @text('All groups') ?>
        </a>
    </li>
    <? foreach($groups as $group) : ?>
    <li>
        <a <? if($state->group == $group->name) echo 'class="active"' ?> href="<?= @route('group='.$group->name) ?>">
            <?= $group->name; ?>
        </a>
    </li>
    <? endforeach ?>
</ul>
<h3><?= @text( 'Details' ); ?></h3>
<p><?= @text('Files').':'.$count ?></p>
<p><?= @text('Size').':'.number_format($size / 1024, 2) ?></p>