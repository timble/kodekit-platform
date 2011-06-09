<?php
/**
 * @version     $Id: form.php 1664 2011-06-08 22:07:31Z gergoerdosi $
 * @category    Nooku
 * @package     Nooku_Server
 * @subpackage  Articles
 * @copyright   Copyright (C) 2011 Timble CVBA and Contributors. (http://www.timble.net).
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://www.nooku.org
 */

defined('KOOWA') or die('Restricted access') ?>

<?= @helper('listbox.radiolist', array(
	'list'      => array((object) array('title' => 'Uncategorized', 'id' => 0)),
	'name'      => 'category_id',
    'text'      => 'title',
	'selected'  => $article->category_id,
    'translate' => true));
?>

<? foreach($folders->find(array('parent_id' => 0)) as $section) : ?>
	<span class="section"><?= @escape($section->title) ?></span><br />
    <?= @helper('listbox.radiolist', array(
    	'list'     => $folders->find(array('parent_id' => $section->id)),
    	'name'     => 'category_id',
        'text'     => 'title',
    	'selected' => $article->category_id));
    ?>
<? endforeach ?>