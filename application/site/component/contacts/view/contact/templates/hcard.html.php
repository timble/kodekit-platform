<?
/**
 * Nooku Framework - http://www.nooku.org
 *
 * @copyright	Copyright (C) 2011 - 2013 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		git://git.assembla.com/nooku-framework.git for the canonical source repository
 */
?>

<address class="vcard">
    <div class="page-header">
        <h1 class="fn url" href="<?= route(); ?>"><?= $contact->name?></h1>
    </div>
    <?if ($contact->con_position) : ?>
    <h2 class="title"><?= $contact->con_position?></h2>
    <? endif;?>
    <? if($contact->isAttachable()) : ?>
        <? foreach($contact->getAttachments() as $item) : ?>
            <? if($item->file->isImage()) : ?>
                <figure>
                    <img class="photo" src="<?= $item->thumbnail->thumbnail ?>" />
                </figure>
            <? endif ?>
        <? endforeach ?>
    <? endif ?>
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
            <span class="type"><?= translate('Phone') ?></span>:
            <span class="value"><?= $contact->telephone?></span>
        </li>
        <? endif; ?>
        <? if ($contact->fax) :?>
        <li class="tel">
            <span class="type"><?= translate('Fax') ?></span>:
            <span class="value"><?= $contact->fax?></span>
        </li>
        <? endif; ?>
        <?if ($contact->mobile) :?>
        <li class="tel">
            <span class="type"><?= translate('Mobile') ?></span>:
            <span class="value"><?= $contact->mobile?></span>
        </li>
        <? endif; ?>
        <?if ($contact->email_to && $contact->params->get('show_email', false)) :?>
        <li>
            <span><?= translate('Email') ?></span>:
            <a class="email" href="mailto:<?= $contact->email_to?>"><?= $contact->email_to?></a>
        </li>
        <? endif; ?>
    </ul>
    <?if ($contact->misc) :?>
    <p class="note">
        <?= $contact->misc ?>
    </p>
    <? endif; ?>
</address>