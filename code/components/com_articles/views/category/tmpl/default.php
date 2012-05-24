<?php // no direct access
defined('_JEXEC') or die('Restricted access');
$cparams =& JComponentHelper::getParams('com_files');
?>

<?php if ($this->params->get('show_page_title', 1)) : ?>
<h1 class="page-header"><?php echo $this->escape($this->params->get('page_title')); ?></h1>
<?php endif; ?>

<?php if ($this->access->canEdit || $this->access->canEditOwn) :
		echo JHTML::_('icon.create', $this->category  , $this->params, $this->access);
endif; ?>

<p>
	<?php if ($this->category->image) : ?>
		<img src="<?php echo $this->baseurl . '/' . $cparams->get('image_path') . '/'. $this->category->image;?>" align="<?php echo $this->category->image_position;?>" hspace="6" alt="<?php echo $this->category->image;?>" />
	<?php endif; ?>
	<?php echo $this->category->description; ?>
</p>

<table width="100%" border="0" cellspacing="0" cellpadding="0" class="table">
	<?php if ($this->params->get('show_headings')) : ?>
	<thead>
	<tr>
		<?php if ($this->params->get('show_title')) : ?>
	 	<th>
			<?php echo JText::_('Item Title'); ?>
		</th>
		<?php endif; ?>
		<?php if ($this->params->get('show_date')) : ?>
		<th width="25%">
			<?php echo JText::_('Date'); ?>
		</th>
		<?php endif; ?>
		<?php if ($this->params->get('show_author')) : ?>
		<th width="20%">
			<?php echo JText::_('Author'); ?>
		</th>
		<?php endif; ?>
	</tr>
	</thead>
	<?php endif; ?>
	<?php foreach ($this->items as $item) : ?>
	<tr>
		<?php if ($this->params->get('show_title')) : ?>
		<?php if ($item->access <= $this->user->get('aid', 0)) : ?>
		<td>
			<a href="<?php echo $item->link; ?>">
				<?php echo $this->escape($item->title); ?></a>
				<?php $this->item = $item; echo JHTML::_('icon.edit', $item, $this->params, $this->access) ?>
		</td>
		<?php else : ?>
		<td>
			<?php
				echo $this->escape($item->title).' : ';
				$link = JRoute::_('index.php?option=com_user&view=login');
				$returnURL = JRoute::_(ContentHelperRoute::getArticleRoute($item->slug, $item->catslug, $item->sectionid), false);
				$fullURL = new JURI($link);
				$fullURL->setVar('return', base64_encode($returnURL));
				$link = $fullURL->toString();
			?>
			<a href="<?php echo $link; ?>">
				<?php echo JText::_( 'Register to read more...' ); ?></a>
		</td>
		<?php endif; ?>
		<?php endif; ?>
		<?php if ($this->params->get('show_date')) : ?>
		<td>
			<?php echo $item->created; ?>
		</td>
		<?php endif; ?>
		<?php if ($this->params->get('show_author')) : ?>
		<td >
			<?php echo $this->escape($item->created_by_alias) ? $this->escape($item->created_by_alias) : $this->escape($item->author); ?>
		</td>
		<?php endif; ?>
	</tr>
	<?php endforeach; ?>
</table>

<?php if ($this->params->get('show_pagination')) : ?>
<p>
	<?php echo $this->pagination->getPagesLinks(); ?>
	<?php echo $this->pagination->getPagesCounter(); ?>
</p>
<?php endif; ?>	