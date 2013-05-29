<?
/**
 * @package     Nooku_Server
 * @subpackage  Contacts
 * @copyright	Copyright (C) 2011 - 2012 Timble CVBA and Contributors. (http://www.timble.net)
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		http://www.nooku.org
 */
?>

<? foreach($contacts as $contact) : ?>
<tr>
    <td>
		<a href="<?= @helper('route.contact', array('row' => $contact)) ?>">
		    <?= $contact->name; ?>
		</a>
	</td>
    <? if ($params->get('show_telephone', true)) : ?>
    <td nowrap="nowrap">
        <?= @escape($contact->telephone); ?>
    </td>
    <? endif; ?>
</tr>
<? endforeach; ?>