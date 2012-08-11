<?
/**
 * @version     $Id: modal.php 3029 2011-10-09 13:07:11Z johanjanssens $
 * @package     Nooku_Server
 * @subpackage  Pages
 * @copyright   Copyright (C) 2011 Timble CVBA and Contributors. (http://www.timble.net).
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://www.nooku.org
 */
?>

<form action="<?= @route('&id='.$page->id) ?>" method="post" name="adminForm">
    <div class="-koowa-container-16">
        <div class="-koowa-grid-16" style="margin-bottom:15px">
            <input type="text" name="title" placeholder="<?= @text('Title') ?>" value="<?= $page->title ?>" size="50" maxlength="255" />
        </div>
    </div>
</form>
