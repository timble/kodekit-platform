<?php
/**
 * @version     $Id$
 * @category    Nooku
 * @package     Nooku_Server
 * @subpackage  Info
 * @copyright   Copyright (C) 2011 Timble CVBA and Contributors. (http://www.timble.net).
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://www.nooku.org
 */
defined('KOOWA') or die( 'Restricted access' ); ?>

<form class="-koowa-grid">
<table class="adminlist">
    <thead>
        <tr>
            <th width="300">
                <?= @text('Setting') ?>
            </th>
            <th>
                <?= @text('Value') ?>
            </th>
        </tr>
    </thead>
    <tbody>
        <? foreach($items as $item) : ?>
            <tr>
                <td>
                    <?= $item->setting ?>
                </td>
                <td>
                    <?= @escape($item->value) ?>
                </td>
            </tr>
        <? endforeach ?>
    </tbody>
</table>
</form>