<?php /** $Id$ */
defined( '_JEXEC' ) or die( 'Restricted access' ); ?>

<?php if ( $this->params->def( 'show_page_title', 1 ) ) : ?>
    <h1 class="componentheading clearer">
		<?php echo $this->escape($this->params->get('page_title')); ?>
	</h1>
<?php endif; ?>

<div id="weblinks-wrap">
<?php if ( @$this->category->image || @$this->category->description ) : ?>
    <div class="desc">
	<?php
		if ( isset($this->category->image) ) :  echo $this->category->image; endif;
		echo $this->category->description;
	?>
    </div>
<?php endif; ?>

<?php echo $this->loadTemplate('items'); ?>

<?php if ($this->params->get('show_other_cats', 1)): ?>
<h3><?php echo JText::_('Browse link categories:') ?></h3>
<ul class="other-categories">
<?php foreach ( $this->categories as $category ) : ?>
	<li class="category">
		<a href="<?php echo $category->link; ?>"><?php echo $this->escape($category->title);?></a>&nbsp;<span class="small">(<?php echo $category->numlinks;?>)</span>
	</li>
<?php endforeach; ?>
</ul>
<?php endif; ?>
</div>