<?php // no direct access
defined('_JEXEC') or die('Restricted access');

$canEdit	= ($this->user->authorize('com_content', 'edit', 'content', 'all') || $this->user->authorize('com_content', 'edit', 'content', 'own'));
?>
<?php if ($this->params->get('show_page_title', 1) && $this->params->get('page_title') != $this->article->title) : ?>
<h1 class="page-header"><?php echo $this->escape($this->params->get('page_title')); ?></h1>
<?php endif; ?>

<?php if ($canEdit || $this->params->get('show_title')  || $this->params->get('show_print_icon')) : ?>
	<?php if ($this->params->get('show_title')) : ?>
	<h2>
		<?php if ($this->params->get('link_titles') && $this->article->readmore_link != '') : ?>
		<a href="<?php echo $this->article->readmore_link; ?>">
			<?php echo $this->escape($this->article->title); ?></a>
		<?php else : ?>
			<?php echo $this->escape($this->article->title); ?>
		<?php endif; ?>
		
		<?php if (!$this->print) : ?>
			<?php if ( $this->params->get( 'show_print_icon' )) : ?>
			<?php echo JHTML::_('icon.print_popup',  $this->article, $this->params, $this->access); ?>
			<?php endif; ?>
			
			<?php if ($canEdit) : ?>
			<?php echo JHTML::_('icon.edit', $this->article, $this->params, $this->access); ?>
			<?php endif; ?>
		<?php else : ?>
			<?php echo JHTML::_('icon.print_screen',  $this->article, $this->params, $this->access); ?>
		<?php endif; ?>
	</h2>
	<?php endif; ?>
<?php endif; ?>

<?php  if (!$this->params->get('show_intro')) :
	echo $this->article->event->afterDisplayTitle;
endif; ?>
<?php echo $this->article->event->beforeDisplayContent; ?>

<?php if (($this->params->get('show_section') && $this->article->sectionid) || ($this->params->get('show_category') && $this->article->catid)) : ?>
<p>
	<?php if ($this->params->get('show_section') && $this->article->sectionid && isset($this->article->section)) : ?>
	<span>
		<?php if ($this->params->get('link_section')) : ?>
			<?php echo '<a href="'.JRoute::_(ContentHelperRoute::getSectionRoute($this->article->sectionid)).'">'; ?>
		<?php endif; ?>
		<?php echo $this->escape($this->article->section); ?>
		<?php if ($this->params->get('link_section')) : ?>
			<?php echo '</a>'; ?>
		<?php endif; ?>
			<?php if ($this->params->get('show_category')) : ?>
			<?php echo ' - '; ?>
		<?php endif; ?>
	</span>
	<?php endif; ?>
	<?php if ($this->params->get('show_category') && $this->article->catid) : ?>
	<span>
		<?php if ($this->params->get('link_category')) : ?>
			<?php echo '<a href="'.JRoute::_(ContentHelperRoute::getCategoryRoute($this->article->catslug, $this->article->sectionid)).'">'; ?>
		<?php endif; ?>
		<?php echo $this->escape($this->article->category); ?>
		<?php if ($this->params->get('link_category')) : ?>
			<?php echo '</a>'; ?>
		<?php endif; ?>
	</span>
	<?php endif; ?>
</p>
<?php endif; ?>

<?php if (($this->params->get('show_author')) && ($this->article->author != "")) : ?>
	<?php JText::printf( 'Written by', ($this->escape($this->article->created_by_alias) ? $this->escape($this->article->created_by_alias) : $this->escape($this->article->author)) ); ?>
<?php endif; ?>

<?php if ($this->params->get('show_create_date')) : ?>
	<?php echo KService::get('koowa:template.helper.date')->format(array('date' => $this->article->created, 'format' => JText::_('DATE_FORMAT_LC2'))) ?>
<?php endif; ?>

<?php if ( intval($this->article->modified) !=0 && $this->params->get('show_modify_date')) : ?>
	<?php echo JText::sprintf('LAST_UPDATED2', KService::get('koowa:template.helper.date')->format(array('date' => $this->article->modified, 'format' => JText::_('DATE_FORMAT_LC2')))); ?>
<?php endif; ?>

<?php if ($this->params->get('show_url') && $this->article->urls) : ?>
	<a href="http://<?php echo $this->article->urls ; ?>" target="_blank"><?php echo $this->escape($this->article->urls); ?></a>
<?php endif; ?>

<?php echo $this->article->text; ?>

<?php echo $this->article->event->afterDisplayContent; ?>
