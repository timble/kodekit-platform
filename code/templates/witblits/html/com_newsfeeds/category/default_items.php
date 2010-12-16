<?php /** $Id$ */
defined( '_JEXEC' ) or die( 'Restricted access' ); ?>

<form action="<?php echo JRoute::_('index.php?view=category&id='.$this->category->slug); ?>" method="post" name="adminForm">
<?php if ($this->params->get('show_limit')) : ?>
	<?php echo JText::_('Display Num') .'&nbsp;'; echo $this->pagination->getLimitBox(); ?>
<?php endif; ?>

<ol class="newsfeed-category">
<?php foreach ($this->items as $item) : ?>
<li class="sectiontableentry<?php echo $item->odd + 1; ?>">

<a href="<?php echo $item->link; ?>" class="category<?php echo $this->escape($this->params->get('pageclass_sfx')); ?>">
<?php echo $this->escape($item->name); ?></a>

<?php if ( $this->params->get( 'show_articles' ) ) : ?>
    &nbsp;<span class="small">(<?php echo $item->numarticles; ?>)</span>
<?php endif; ?>
</li>
<?php endforeach; ?>
</ol>

<div id="pagination-wrap">
	<div class="pagination-links">
		<?php echo $this->pagination->getPagesCounter(); ?>
	</div>
	<?php echo $this->pagination->getPagesLinks(); ?>
</div>	
</form>