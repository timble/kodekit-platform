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

<!--
<script src="media://lib_koowa/js/koowa.js" />
-->

<? if ($contact->name && $contact->params->get('show_name')) : ?>
<h1><?= $contact->name; ?></h1>
<? endif;?>

<?if ($contact->image && $contact->params->get('show_image')) : ?>
<div style="float: right;">
	<img src="images/stories/<?= $contact->image ?>" title="@text( 'Contact' )" />
</div>
<? endif;?>

<?if ($contact->con_position && $contact->params->get('show_position')) : ?>
<h2><?= $contact->con_position?></h2>
<? endif;?>

<p>
    <? if ($contact->address && $contact->params->get('show_street_address')) : ?>
    <?= $contact->address?><br />
    <? endif; ?>
    <? if ( $contact->suburb && $contact->params->get('show_suburb')) : ?>
    <?= $contact->suburb?><br />
    <? endif; ?>
    <? if ($contact->state && $contact->params->get('show_state')) : ?>
    <?= $contact->state?><br />
    <? endif; ?>
    <?if ($contact->postcode && $contact->params->get('show_postcode')) : ?>
        <?= $contact->postcode?><br />
    <? endif; ?>
    <? if ($contact->country && $contact->params->get('show_country')) : ?>
        <?= $contact->country?><br />
    <? endif; ?>
</p>

<ul>
    <? if ($contact->telephone && $contact->params->get('show_telephone')) :?>
    <li><?= $contact->telephone?></li>
    <? endif; ?>
    <? if ($contact->fax && $contact->params->get('show_fax')) :?>
    <li><?= $contact->fax?></li>
    <? endif; ?>
    <?if ($contact->mobile && $contact->params->get('show_mobile')) :?>
    <li><?= $contact->mobile?></li>
    <? endif; ?>
    <?if ($contact->email_to && $contact->params->get('show_email')) :?>
    <li><a href="mailto:<?= $contact->email_to?>"><?= $contact->email_to?></a></li>
    <? endif; ?>
    
</ul>

<?if ($contact->fax && $contact->params->get('show_misc')) :?>
<p>
    <?= $contact->misc ?>
</p>
<? endif; ?> 
               
<?if ($contact->params->get('allow_vcard')) :?>
<p>
    <?= @text( 'Download information as a' );?>
    <a href="<?= @route('id='.$contact->id.'&format=vcard') ?>">
        <?= @text( 'VCard' );?>
    </a>
</p>
<? endif; ?>

<? if ( $contact->params->get('show_email_form') && ($contact->email_to || $contact->user_id)) : ?>
    <?= @service('com://site/contacts.controller.message')->layout('form')->display(); ?>
<? endif; ?>   
