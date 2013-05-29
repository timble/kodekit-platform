<?
/**
 * @package     Nooku_Server
 * @subpackage  Application
 * @copyright   Copyright (C) 2011 - 2012 Timble CVBA and Contributors. (http://www.timble.net).
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://www.nooku.org
 */
?>

<? if(count($messages)) : ?>
    <? foreach ($messages as $type => $message) : ?>
        <div class="alert alert-<?= strtolower($type) ?>">
            <? foreach ($message as $line) : ?>
                <?= $line ?>
            <? endforeach; ?>
        </div>
    <? endforeach; ?>
<? endif; ?>