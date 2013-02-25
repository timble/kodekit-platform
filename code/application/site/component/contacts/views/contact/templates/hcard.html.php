<?
/**
 * @package     Nooku_Server
 * @subpackage  Contacts
 * @copyright	Copyright (C) 2011 - 2012 Timble CVBA and Contributors. (http://www.timble.net)
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		http://www.nooku.org
 */
?>

<address class="vcard">
    <h1 class="fn url" href="<?= @route(); ?>"><?= $contact->name?></h1>
    <?if ($contact->con_position) : ?>
    <h2 class="title"><?= $contact->con_position?></h2>
    <? endif;?>
    <?if ($contact->image) : ?>
    <div style="float: right;">
        <img class="photo" src="images/stories/<?= $contact->image ?>" title="<?= $contact->name ?>" />
    </div>
    <? endif;?>
    <div class="adr">
        <? if ($contact->address) : ?>
        <div class="street-address"><?= $contact->address?></div>
        <? endif; ?>
        <? if ( $contact->suburb) : ?>
        <span class="locality"><?= $contact->suburb?></span>,
        <? endif; ?>
        <? if ($contact->state) : ?>
        <span class="region"> <?= $contact->state?></span>
        <? endif; ?>
        <?if ($contact->postcode) : ?>
        <span class="postal-code"><?= $contact->postcode?></span>
        <? endif; ?>
        <? if ($contact->country) : ?>
        <div class="country-name"><?= $contact->country?></div>
        <? endif; ?>
    </div>
    <ul>
        <? if ($contact->telephone) :?>
        <li class="tel">
            <span class="type"><?= @text('Phone') ?></span>:
            <span class="value"><?= $contact->telephone?></span>
        </li>
        <? endif; ?>
        <? if ($contact->fax) :?>
        <li class="tel">
            <span class="type"><?= @text('Fax') ?></span>:
            <span class="value"><?= $contact->fax?></span>
        </li>
        <? endif; ?>
        <?if ($contact->mobile) :?>
        <li class="tel">
            <span class="type"><?= @text('Mobile') ?></span>:
            <span class="value"><?= $contact->mobile?></span>
        </li>
        <? endif; ?>
        <?if ($contact->email_to && $contact->params->get('show_email')) :?>
        <li>
            <span><?= @text('Email') ?></span><a class="email" href="mailto:<?= $contact->email_to?>"><?= $contact->email_to?></a>
        </li>
        <? endif; ?>
    </ul>
    <?if ($contact->misc) :?>
    <p class="note">
        <?= $contact->misc ?>
    </p>
    <? endif; ?>
</address>