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

defined('KOOWA') or die('Restricted access'); ?>

<table class="adminlist">
<thead>
	<tr>
		<th>
			<strong><?= @text( 'Latest Items' ); ?></strong>
		</th>
		<th>
			<strong><?= @text( 'Created' ); ?></strong>
		</th>
		<th>
			<strong><?= @text( 'Author' ); ?></strong>
		</th>
	</tr>
</thead>
<tbody>
<?php foreach ($articles as $article) : ?>
	<tr>
		<td>
			<a href="<?= @route('index.php?option=com_articles&view=article&id='. $article->id); ?>">
				<?= @escape($article->title);?>
			</a>
		</td>
		<td>
			<?= @helper('com://admin/articles.template.helper.date.humanize', array('date' => $article->created_on));?>
		</td>
		<td>
			<? if (KFactory::get('joomla:user')->authorize( 'com_users', 'manage' )) : ?>
			   	<a href="<?= @route('index.php?option=com_users&view=user&id='. $article->created_by); ?>" title="<?= @text( 'Edit User' ) ?>">
			   		<?= @escape($article->created_by_name) ?>
			   	</a>
		    <? else : ?>
	            <?= @escape($article->created_by_name) ?>
	        <? endif; ?>
		</td>
	</tr>
<? endforeach; ?>
</tbody>
</table>