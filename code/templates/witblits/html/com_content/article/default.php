<?php /** $Id$ */
defined( '_JEXEC' ) or die( 'Restricted access' ); ?>

<?php
include_once(dirname(__FILE__).'/../icon.php');
$show_page_title 		= $this->params->get('show_page_title',1) && $this->params->get('page_title') != $this->article->title;
$show_title 			= $this->params->get('show_title');
$can_edit 				= $this->user->authorize('com_content', 'edit', 'content', 'all') || $this->user->authorize('com_content', 'edit', 'content', 'own');
$link_titles 			= $this->params->get('link_titles') && $this->article->readmore_link != '';
$show_modified_date 	= intval($this->article->modified) !=0 && $this->params->get('show_modify_date');
$show_author 			= $this->params->get('show_author') && ($this->article->author != "");
$show_create_date		= $this->params->get('show_create_date');
$show_icons				= $this->params->get('show_icons');
$show_print				= $this->params->get('show_print_icon');
$show_email				= $this->params->get('show_email_icon');
$show_section			= $this->params->get('show_section') && $this->article->sectionid;
$link_section			= $this->params->get('link_section');
$show_category			= $this->params->get('show_category') && $this->article->catid;
$link_category			= $this->params->get('link_category');
?>
<div class="article-page">
   
    <?php if ($this->print) :
    	echo '<span class="print-icon">' . JHTML::_('icon.print_screen', $this->article, $this->params, $this->access) . '</span>';
    	endif; ?>
    
    <?php if ($show_title == 1) : ?>
    <h1 class="article-title"><?php echo $this->escape($this->article->title); ?><?php if ($can_edit) : ?> <?php echo articleIcons::edit($this->article, $this->params, $this->access); ?><?php endif; ?></h1>
    <?php endif; ?>
    
    <?php if($show_author || $show_create_date || $show_section || $show_category || $show_print || $show_email) : ?>
    
    <p class="article-info<?php if ($show_icons) echo ' icons' ?>">
        <?php if ($show_author) : ?><?php echo JText::_('By '); ?>
        	<strong><?php JText::printf($this->article->created_by_alias ? $this->article->created_by_alias : $this->article->author); ?></strong>
        <?php endif; ?> 
        <?php if ($show_create_date) echo JText::_('on ').JHTML::_('date', $this->article->created, JText::_('%B %d, %Y')) . '.'; ?>
        <?php if ($show_section || $show_category) : ?>
        	<?php if ($show_section && $show_category) echo JText::_('Posted in: '); ?>
        	<?php if ($show_section) : ?>	
        		<?php if ($link_section) : echo '<a href="'.JRoute::_(ContentHelperRoute::getSectionRoute($this->article->sectionid)).'">'; endif; ?>
        			<span class="article-section"><?php echo $this->article->section; ?></span>
        		<?php if ($link_section) : echo '</a>'; endif; ?>
        	<?php endif; ?>
    		<?php if ($show_section && $show_category) echo ' / ' ?>
            <?php if ($show_category) : ?>
        		<?php if ($link_category) echo '<a href="'.JRoute::_(ContentHelperRoute::getCategoryRoute($this->article->catslug, $this->article->sectionid)).'">'; ?>
        			<span class="article-category"><?php echo $this->escape($this->article->category); ?></span>
        		<?php if ($link_category) echo '</a>'; ?>
        	<?php endif; ?>.
        <?php endif; ?>
        <?php if ($show_print) : ?>
        <span class="icon print"><?php echo articleIcons::print_popup($this->article, $this->params, $this->access); ?></span>
        <?php endif; ?>
        <?php if ($show_email) : ?>
        <span class="icon email"><?php echo articleIcons::email($this->article, $this->params, $this->access); ?></span>
        <?php endif; ?>
    </p>
    <?php endif; ?>
    
	<?php  if (!$this->params->get('show_intro')) :	echo $this->article->event->afterDisplayTitle; endif; ?>

	<div class="article-body clearer<?php if (isset ($this->article->toc)) : ?> toc<?php endif; ?>" id="article">
		<?php echo $this->article->event->beforeDisplayContent; ?>

		<?php if (isset ($this->article->toc)) : ?>
			<?php echo $this->article->toc; ?>
		<?php endif; ?>
		
		<div id="article-content">
		<?php echo $this->article->text; ?>

		<?php if ( intval($this->article->modified) !=0 && $this->params->get('show_modify_date')) : ?>
		<p class="modified"><?php echo JText::sprintf('LAST_UPDATED2', JHTML::_('date', $this->article->modified, JText::_('DATE_FORMAT_LC2'))); ?>.</p>
	    <?php endif; ?>
		</div>
		<?php echo $this->article->event->afterDisplayContent; ?>
	</div>
</div>