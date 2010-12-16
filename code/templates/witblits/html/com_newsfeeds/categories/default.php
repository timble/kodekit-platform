<?php /** $Id$ */
defined( '_JEXEC' ) or die( 'Restricted access' ); ?>

<?php if ( $this->params->get( 'show_page_title', 1 ) ) : ?>
<h1 class="componentheading clearer"><?php echo $this->escape($this->params->get('page_title')); ?></h1>
<?php endif; ?>

<?php if ( ($this->params->get('image') != -1) || $this->params->get('show_comp_description') ) : ?>
<div class="desc">
	<?php if(isset($this->image)): echo $this->image; endif; echo $this->escape($this->params->get('comp_description'));?>
</div>
<?php endif; ?>
<ul class="feeds-list">
    <?php foreach ( $this->categories as $category ) : ?>
	<li class="category"><a href="<?php echo $category->link ?>"><?php echo $this->escape($category->title);?></a>
	<?php if ( $this->params->get( 'show_cat_items' ) ) : ?>&nbsp;<span class="small">(<?php echo $category->numlinks;?>)</span><?php endif; ?>
	<?php if ( $this->params->get( 'show_cat_description' ) && $category->description ) : ?>
	<p class="feed-desc"><?php echo $category->description; ?></p>
	<?php endif; ?>
	</li>
    <?php endforeach; ?>
</ul>