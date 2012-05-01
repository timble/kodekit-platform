<?
/**
 * @version		$Id: default.php 3537 2012-04-02 17:56:59Z johanjanssens $
 * @category	Nooku
 * @package     Nooku_Server
 * @subpackage  Contacts
 * @copyright	Copyright (C) 2011 - 2012 Timble CVBA and Contributors. (http://www.timble.net)
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		http://www.nooku.org
 */

defined('KOOWA') or die('Restricted access'); ?>

<script src="media://lib_koowa/js/koowa.js" />

<?if ($contact->image && $contact->params->get( 'show_image' ) ) : ?>
<div style="float: right;">
	<img src="images/stories/<?= $contact->image ?>" title="@text( 'Contact' )" />
</div>
<? endif;?>

<div id="component-contact">
<table width="100%" cellpadding="0" cellspacing="0" border="0" class="contentpaneopen"">
<? if ($contact->name && $contact->params->get( 'show_name' ) ) : ?>
<tr>
	<td class="contentheading" width="100%">
        <?= $contact->name; ?>
    </td>
</tr>
<? endif;?>
<?if ($contact->con_position && $contact->params->get( 'show_position' ) ) : ?>
<tr>
	<td colspan="2">
        <?= $contact->con_position?>
    </td>
</tr>
<? endif;?>
<? if ($contact->address && $contact->params->get( 'show_street_address' ) ) : ?>
<tr>
	<td valign="top" width="40">
        <?= $contact->address?>
    </td>
</tr>
<? endif;?>
<? if ( $contact->suburb && $contact->params->get( 'show_suburb' ) ) : ?>
<tr>
	<td valign="top">
        <?= $contact->suburb?>
    </td>
</tr>
<? endif; ?>
<? if ( $contact->state && $contact->params->get( 'show_state' ) ) : ?>
<tr>
	<td valign="top">
        <?= $contact->state?>
    </td>
</tr>
<? endif; ?>
<?if ( $contact->postcode && $contact->params->get( 'show_postcode' ) ) : ?>
<tr>
	<td valign="top">
        <?= $contact->postcode?>
    </td>
</tr>
<? endif; ?>
<? if ( $contact->country && $contact->params->get( 'show_country' ) ) : ?>
<tr>
	<td valign="top">
        <?= $contact->country?>
    </td>
</tr>
<? endif; ?>
</table>
<br />
<table>
<?if ($contact->email_to && $contact->params->get( 'show_email' )) :?>
<tr>
	<td>
    	<a href="mailto:<?= $contact->email_to?>"><?= $contact->email_to?></a>
    </td>
</tr>
<? endif; ?>
<? if ($contact->telephone && $contact->params->get( 'show_telephone')) :?>
<tr>
	<td>
        <?= $contact->telephone?>
    </td>
</tr>
<? endif; ?>
<?if ($contact->mobile && $contact->params->get( 'show_mobile')) :?>
<tr>
	<td>
        <?= $contact->mobile?>
    </td>
</tr>
<? endif; ?>
<? if ($contact->fax && $contact->params->get( 'show_fax')) :?>
<tr>
	<td>
        <?= $contact->fax?>
    </td>
</tr>
<? endif; ?>
<?if ($contact->webpage && $contact->params->get( 'show_webpage')) :?>
<tr>
	<td width="<?= $contact->params->get( 'column_width' ); ?>" ></td>
    <td>
    	<a href="<?= 'http://'.$contact->webpage?>" target="_blank">
            <?= "http://".$contact->webpage?>
        </a>
    </td>
</tr>
<? endif; ?>
</table>
<br />
<table>
<?if ($contact->fax && $contact->params->get( 'show_misc')) :?>
<tr>
	<td>
        <?= $contact->misc ?>
    </td>
</tr>
<? endif; ?>                
<?if ($contact->params->get( 'allow_vcard')) :?>
<tr>
	<td colspan="2">
        <?= @text( 'Download information as a' );?>
        <a href="<?= @route('id='.$contact->id.'&format=vcard') ?>">
            <?= @text( 'VCard' );?>
        </a>
    </td>
</tr>
</table>
</div>
<? endif; ?>
<? if ( $contact->params->get('show_email_form') && ($contact->email_to || $contact->user_id)) : ?>
    <?= @service('com://site/contacts.controller.mail')->view('mail')->layout('form')->display(); ?>
<? endif; ?>   
