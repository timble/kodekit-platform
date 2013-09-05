<?
/**
 * Nooku Framework - http://www.nooku.org
 *
 * @copyright	Copyright (C) 2011 - 2013 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		git://git.assembla.com/nooku-framework.git for the canonical source repository
 */
?>

<? foreach($contacts as $contact) : ?>
<tr>
    <td>
		<a href="<?= helper('route.contact', array('row' => $contact)) ?>">
		    <?= $contact->name; ?>
		</a>
	</td>
    <? if ($params->get('show_telephone', true)) : ?>
    <td nowrap="nowrap">
        <?= escape($contact->telephone); ?>
    </td>
    <? endif; ?>
</tr>
<? endforeach; ?>