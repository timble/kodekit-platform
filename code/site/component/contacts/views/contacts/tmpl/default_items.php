<?
/**
 * @version		$Id: default_items.php 3537 2012-04-02 17:56:59Z johanjanssens $
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
    <? if ($params->get('show_email')) : ?>
	<td nowrap="nowrap">
	    <?= $contact->email_to; ?>
	</td>
    <? endif; ?>
    <? if ($params->get('show_telephone')) : ?>
    <td nowrap="nowrap">
        <?= @escape($contact->telephone); ?>
    </td>
    <? endif; ?>
</tr>
<? endforeach; ?>