<?
/**
 * @version		$Id: default_items.php 3537 2012-04-02 17:56:59Z johanjanssens $
 * @package     Nooku_Server
 * @subpackage  Contacts
 * @copyright	Copyright (C) 2011 - 2012 Timble CVBA and Contributors. (http://www.timble.net)
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		http://www.nooku.org
 */

defined('KOOWA') or die('Restricted access'); ?>

<? foreach( $contacts as $contact ) : ?>
<tr>
    <td height="20">
		<a href="<?= @helper('route.contact', array('row' => $contact)) ?>">
		    <?= $contact->name; ?>
		</a>
	</td>    
	<? if ( $params->get( 'show_position' ) ) : ?>
	<td>
        <?= @escape($contact->con_position);?>
	</td>
    <? endif; ?>
    <? if ( $params->get( 'show_email' ) ) : ?>
	<td width="20%">
	    <?= $contact->email_to; ?>
	</td>
    <? endif; ?>
    <? if ( $params->get( 'show_telephone' ) ) : ?>
    <td width="15%">
        <?= @escape($contact->telephone); ?>
    </td>
    <? endif; ?>
</tr>
<? endforeach; ?>