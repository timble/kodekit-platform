<?
/**
 * @version		$Id$
 * @package     Nooku_Server
 * @subpackage  Contacts
 * @copyright	Copyright (C) 2011 - 2012 Timble CVBA and Contributors. (http://www.timble.net)
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		http://www.nooku.org
 */

defined('KOOWA') or die('Restricted access'); ?>

<address class="vcard">
    <? if ($contact->name && $contact->params->get('show_name')) : ?>
    <h1 class="fn url" href="<?= @route(); ?>"><?= $contact->name?></h1>
    <? endif;?>
    <?if ($contact->con_position && $contact->params->get('show_position')) : ?>
    <h2 class="title"><?= $contact->con_position?></h2>
    <? endif;?>
    <?if ($contact->image && $contact->params->get('show_image')) : ?>
    <div style="float: right;">
        <img class="photo" src="images/stories/<?= $contact->image ?>" title="<?= $contact->name ?>" />
    </div>
    <? endif;?>
    <div class="adr">
        <span class="type">Work</span>:
        <? if ($contact->address && $contact->params->get('show_street_address')) : ?>
        <div class="street-address"><?= $contact->address?></div>
        <? endif; ?>
        <? if ( $contact->suburb && $contact->params->get('show_suburb')) : ?>
        <span class="locality"><?= $contact->suburb?></span>,
        <? endif; ?>
        <? if ($contact->state && $contact->params->get('show_state')) : ?>
        <span class="region"> <?= $contact->state?></span>&nbsp;&nbsp;
        <? endif; ?>
        <?if ($contact->postcode && $contact->params->get('show_postcode')) : ?>
        <span class="postal-code"><?= $contact->postcode?></span>
        <? endif; ?>
        <? if ($contact->country && $contact->params->get('show_country')) : ?>
        <div class="country-name"><?= $contact->country?></div>
        <? endif; ?>
    </div>
    <ul>
        <? if ($contact->telephone && $contact->params->get('show_telephone')) :?>
        <li class="tel">
            <span class="type">Work</span>:
            <span class="value"><?= $contact->telephone?></span>
        </li>
        <? endif; ?>
        <? if ($contact->fax && $contact->params->get('show_fax')) :?>
        <li class="tel">
            <span class="type">Fax</span>:
            <span class="value"><?= $contact->fax?></span>
        </li>
        <? endif; ?>
        <?if ($contact->mobile && $contact->params->get('show_mobile')) :?>
        <li class="tel">
            <span class="type">Cell</span>:
            <span class="value"><?= $contact->mobile?></span>
        </li>
        <? endif; ?>
        <?if ($contact->email_to && $contact->params->get('show_email')) :?>
        <li>
            <span>Email</span><a class="email" href="mailto:<?= $contact->email_to?>"><?= $contact->email_to?></a>
        </li>
        <? endif; ?>
    </ul>
    <?if ($contact->fax && $contact->params->get('show_misc')) :?>
    <p class="note">
        <?= $contact->misc ?>
    </p>
    <? endif; ?>
</address>