<?php
/**
 * @version     $Id$
 * @category    Nooku
 * @package     Nooku_Server
 * @subpackage  Articles
 * @copyright   Copyright (C) 2011 Timble CVBA and Contributors. (http://www.timble.net).
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://www.nooku.org
 */

defined('KOOWA') or die('Restricted access') ?>

<table width="100%" class="paramlist admintable" cellspacing="1">
    <tbody>
        <tr>
            <td width="40%" class="paramlist_key">
                <label for="meta_description"><?= @text('Description') ?></label>
            </td>
            <td class="paramlist_value">
                <textarea name="meta_description" cols="30" rows="5" class="text_area"><?= $article->meta_description ?></textarea>
            </td>
        </tr>
        <tr>
            <td width="40%" class="paramlist_key">
                <label for="meta_keywords"><?= @text('Keywords') ?></label>
            </td>
            <td class="paramlist_value">
                <textarea name="meta_keywords" cols="30" rows="5" class="text_area"><?= $article->meta_keywords ?></textarea>
            </td>
        </tr>
        <tr>
            <td width="40%" class="paramlist_key">
                <label for="meta_robots"><?= @text('Robots') ?></label>
            </td>
            <td class="paramlist_value">
                <input type="text" name="meta_robots" value="<?= $article->meta_robots ?>" class="text_area" size="20">
            </td>
        </tr>
        <tr>
            <td width="40%" class="paramlist_key">
                <label for="meta_author"><?= @text('Author') ?></label>
            </td>
            <td class="paramlist_value">
                <input type="text" name="meta_author" value="<?= $article->meta_author ?>" class="text_area" size="20">
            </td>
        </tr>
    </tbody>
</table>