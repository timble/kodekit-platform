<?php /** $Id$ */
defined( '_JEXEC' ) or die( 'Restricted access' ); ?>

<?php if ( $this->params->def( 'show_page_title', 1 ) ) : ?>
    <h1 class="componentheading clearer">
		<?php echo $this->escape($this->params->get('page_title')); ?>
	</h1>
<?php endif; ?>

<?php if ( ($this->params->def('image', -1) != -1) || $this->params->def('show_comp_description', 1) ) : ?>
<div class="desc">
	<?php if ( isset($this->image) ) : echo $this->image; endif;
		echo $this->params->get('comp_description');
	?>
</div>
<?php endif; ?>
<ul id="weblinks">
<?php foreach ( $this->categories as $category ) : ?>
	<li class="category"><a href="<?php echo $category->link; ?>">
		<?php echo $this->escape($category->title);?></a>
		&nbsp;<span class="small">(<?php echo $category->numlinks;?>)</span>
	</li>
<?php endforeach; ?>
</ul>