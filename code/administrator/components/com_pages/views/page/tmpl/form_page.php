<?php
/**
 * @version     $Id: form_page.php 3035 2011-10-09 16:57:12Z johanjanssens $
 * @category    Nooku
 * @package     Nooku_Server
 * @subpackage  Pages
 * @copyright   Copyright (C) 2011 Timble CVBA and Contributors. (http://www.timble.net).
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://www.nooku.org
 */

defined('KOOWA') or die('Restricted access') ?>

<?= @helper('tabs.startPanel', array('title' => 'System')); ?>
<section>
    <fieldset>
        <?= $page->params_page->render('params'); ?>
    </fieldset>
</section>
<?= @helper('tabs.endPanel'); ?>
