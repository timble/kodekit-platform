<?php /** $Id$ */
defined( '_JEXEC' ) or die( 'Restricted access' ); ?>

<?php
include_once(dirname(__FILE__).'/../icon.php');
$canEdit = ($this->user->authorize('com_content', 'edit', 'content', 'all') || $this->user->authorize('com_content', 'edit', 'content', 'own'));
?>
<?php if ($this->item->state == 0) : ?>
<div class="system-unpublished">
<?php endif; ?>
	<?php if ($this->item->params->get('show_title')) : ?>
	<h2 class="contentheading reset">
		<?php if ($this->item->params->get('link_titles') && $this->item->readmore_link != '') : ?>
			<a href="<?php echo $this->item->readmore_link; ?>"><?php echo $this->escape($this->item->title); ?></a>
		<?php else : ?>
			<?php echo $this->escape($this->item->title); ?>
		<?php endif; ?>
    	<?php if ($canEdit) : ?>
    		<?php echo articleIcons::edit($this->item, $this->params, $this->access); ?>
    	<?php endif; ?>
	</h2>
	<?php endif; ?>
   	<?php if ($this->params->get('show_author') || $this->params->get('show_create_date') || $this->params->get('show_section') || $this->params->get('show_category') || $this->params->get('show_pdf_icon') || $this->params->get('show_print_icon') || $this->params->get('show_email_icon')) : ?>
    <p class="article-info<?php if ($this->params->get('show_icons')) echo ' icons' ?>">
	    <?php if (($this->item->params->get('show_author')) && ($this->item->author != "")) : ?><?php echo JText::_('By '); ?>
	    	<strong><?php JText::printf($this->item->created_by_alias ? $this->item->created_by_alias : $this->item->author); ?></strong>
	    <?php endif; ?> 
	    <?php if ($this->item->params->get('show_create_date')) echo JText::_('on ').JHTML::_('date', $this->item->created, JText::_('%B %d, %Y')) . '.'; ?>
	    <?php if (($this->item->params->get('show_section') && $this->item->sectionid) || ($this->item->params->get('show_category') && $this->item->catid)) : ?>
	    	<?php if (($this->item->params->get('show_section') && $this->item->sectionid) || ($this->item->params->get('show_category') && $this->item->catid)) echo JText::_('Posted in '); ?>
	    	<?php if ($this->item->params->get('show_section') && $this->item->sectionid && isset($this->item->section)) : ?>		
	    		<?php if ($this->item->params->get('link_section')) : echo '<a href="'.JRoute::_(ContentHelperRoute::getSectionRoute($this->item->sectionid)).'">'; endif; ?>
	    			<span class="article-section"><?php echo $this->item->section; ?></span>
	    		<?php if ($this->item->params->get('link_section')) : echo '</a>'; endif; ?>
	    	<?php endif; ?>
			<?php if (($this->item->params->get('show_section') && $this->item->sectionid) && ($this->item->params->get('show_category') && $this->item->catid)) echo ' / ' ?>
	        <?php if ($this->item->params->get('show_category') && $this->item->catid) : ?>
	    		<?php if ($this->item->params->get('link_category')) echo '<a href="'.JRoute::_(ContentHelperRoute::getCategoryRoute($this->item->catslug, $this->item->sectionid)).'">'; ?>
	    			<span class="article-category"><?php echo $this->item->category; ?></span>
	    		<?php if ($this->item->params->get('link_category')) echo '</a>'; ?>
	    	<?php endif; ?>.
	    <?php endif; ?>
	    <?php if ($this->params->get('show_pdf_icon')) : ?>
	    <span class="icon pdf"><?php echo articleIcons::pdf($this->item, $this->params, $this->access); ?></span>
	    <?php endif; ?>
	    <?php if ($this->params->get('show_print_icon')) : ?>
	    <span class="icon print"><?php echo articleIcons::print_popup($this->item, $this->params, $this->access); ?></span>
	    <?php endif; ?>
	    <?php if ($this->params->get('show_email_icon')) : ?>
	    <span class="icon email"><?php echo articleIcons::email($this->item, $this->params, $this->access); ?></span>
	    <?php endif; ?>
    </p>
	<?php endif; ?>
	<?php  if (!$this->item->params->get('show_intro')) echo $this->item->event->afterDisplayTitle; ?>
	<?php echo $this->item->event->beforeDisplayContent; ?>
	<?php if (isset ($this->item->toc)) echo $this->item->toc; ?>
	<?php echo $this->item->text; ?>
	<?php if ( intval($this->item->modified) != 0 && $this->item->params->get('show_modify_date')) : ?>
		<p class="modified"><?php echo JText::sprintf('LAST_UPDATED2', JHTML::_('date', $this->item->modified, JText::_('DATE_FORMAT_LC2'))); ?>.</p>
	<?php endif; ?>
    <?php if ($this->item->params->get('show_readmore') && $this->item->readmore) : ?>
	    <p class="readon">    
    		<a href="<?php echo $this->item->readmore_link; ?>" title="<?php echo JText::sprintf($this->item->title); ?>" class="tooltip">
			<?php if ($this->item->readmore_register) :
				echo JText::_('Register to read more...');
			elseif ($readmore = $this->item->params->get('readmore')) :
				echo $readmore ;
			else :
				echo JText::sprintf('Continue Reading', '<span>', $this->escape($this->item->title), '</span>');
			endif; ?> &#8594;</a>
	    </p>
    <?php endif; ?>
	<?php if ($this->item->state == 0) : ?>
</div>
<?php endif; ?>
<?php echo $this->item->event->afterDisplayContent; ?>