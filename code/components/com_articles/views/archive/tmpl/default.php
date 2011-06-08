<?php
/**
 * @version     $Id: form.php 1638 2011-06-07 23:00:45Z johanjanssens $
 * @category    Nooku
 * @package     Nooku_Server
 * @subpackage  Articles
 * @copyright   Copyright (C) 2011 Timble CVBA and Contributors. (http://www.timble.net).
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://www.nooku.org
 */

defined('KOOWA') or die('Restricted access') ?>

<form id="jForm" action="<?= JRoute::_('index.php')?>" method="post">
    <? if ($this->params->get('show_page_title', 1)) : ?>
	<div class="componentheading<?= @escape($this->params->get('pageclass_sfx')); ?>">
	    <?= @escape($this->params->get('page_title')); ?>
	</div>
    <? endif; ?>
	<p>
		<? if ($this->params->get('filter')) : ?>
		    <?= @text('Filter').'&nbsp;'; ?>
			<input type="text" name="filter" value="<?= @escape($this->filter); ?>" class="inputbox" onchange="document.jForm.submit();" />
		<? endif; ?>
		
		<?= $this->form->monthField; ?>
		<?= $this->form->yearField; ?>
		<?= $this->form->limitField; ?>
		
		<button type="submit" class="button"><?= @text('Filter'); ?></button>
	</p>

<?= @template('default_items'); ?>

<input type="hidden" name="view" value="archive" />
<input type="hidden" name="option" value="com_content" />
<input type="hidden" name="viewcache" value="0" />
</form>