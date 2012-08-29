<?
/**
 * @version     $Id: form_modules.php 3030 2011-10-09 13:21:09Z johanjanssens $
 * @package     Nooku_Server
 * @subpackage  Pages
 * @copyright   Copyright (C) 2011 Timble CVBA and Contributors. (http://www.timble.net).
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://www.nooku.org
 */
?>
<div class="control-group">
    <label class="control-label" for="parent"><?= @text('Page') ?></label>
    <div id="parent" class="controls">
        <?= @helper('listbox.pages', array('name' => 'link_id', 'selected' => $page->link_id)) ?>
    </div>
</div>
