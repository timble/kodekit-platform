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

<? foreach($folders as $folder) : ?>
	<span class="section"><?= @escape($folder->title); ?></span><br />
	<? if($folder->hasChildren()) : ?>
		<?= @helper('listbox.radiolist', array(
				'list'     => $folder->getChildren(),
				'selected' => $article->category_id,
				'name'     => 'category_id',
		        'text'     => 'title',
			));
		?>
	<? endif; ?>
<? endforeach ?>