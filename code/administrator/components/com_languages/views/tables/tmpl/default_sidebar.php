<?
/**
 * @version     $Id$
 * @category    Nooku
 * @package     Nooku_Server
 * @subpackage  Languages
 * @copyright   Copyright (C) 2011 - 2012 Timble CVBA and Contributors. (http://www.timble.net).
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://www.nooku.org
 */
?>

<div class="sidebar">
    <h3><?= @text('Status')?></h3>
    <ul class="scrollable">
        <li class="<?= $state->translated !== false ? 'active' : '' ?>">
            <a href="<?= @route('translated=1') ?>"><?= @text('Translated') ?></a>
        </li>
        <li class="<?= $state->translated === false ? 'active' : '' ?>">
            <a href="<?= @route('translated=0') ?>"><?= @text('Untranslated') ?></a>
        </li>
    </ul>
</div>