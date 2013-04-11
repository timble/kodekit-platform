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
<dl id="system-message">
<? foreach ($messages as $type => $message) : ?>
	<dt class="<?= strtolower($type) ?>"><?= @text( $type ) ?></dt>
	<dd class="<?= strtolower($type) ?> message fade">
	<ul>
    <? foreach ($message as $line) : ?>
        <li><?= $line ?></li>
    <? endforeach; ?>
    </ul>
	</dd>
<? endforeach; ?>
</dl>
<? endif; ?>