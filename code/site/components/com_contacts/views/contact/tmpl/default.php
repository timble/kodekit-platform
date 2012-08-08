<?
/**
 * @version		$Id: default.php 3537 2012-04-02 17:56:59Z johanjanssens $
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

<? if ($contact->params->get('allow_vcard')) : ?>
    <link href="<?= @route('format=vcard') ?>" rel="alternate" type="text/x-vcard; version=2.1" title="Vcard - <?= $contact->name; ?>"/>
<? endif; ?>

<?= @template('hcard') ?>
               
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
