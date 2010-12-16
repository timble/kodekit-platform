<?php /** $Id$ */
defined( '_JEXEC' ) or die( 'Restricted access' ); ?>

<?php if ($this->params->get('show_page_title',1)) : ?>
<h1 class="componentheading frontpage clearer">
	<?php echo $this->escape($this->params->get('page_title')); ?>
</h1>
<?php endif; ?>

<div class="frontpage-blog blog clearer">
	<?php $i = $this->pagination->limitstart; $rowcount = $this->params->def('num_leading_articles', 1); 
	for ($y = 0; $y < $rowcount && $i < $this->total; $y++, $i++) : ?>
	<div class="leading clearer">
		<?php $this->item =& $this->getItem($i, $this->params); echo $this->loadTemplate('item'); ?>
	</div>
	<?php endfor; ?>

	<?php $introcount = $this->params->def('num_intro_articles', 4);
	if ($introcount) :
	$colcount = $this->params->def('num_columns', 2);
	if ($colcount == 0) :
		$colcount = 1;
	endif;
	$rowcount = (int) $introcount / $colcount;
	$ii = 0;
	for ($y = 0; $y < $rowcount && $i < $this->total; $y++) : ?>
	
	<div class="article-row clearer">
		<?php for ($z = 0; $z < $colcount && $ii < $introcount && $i < $this->total; $z++, $i++, $ii++) : ?>
			<div id="article<?php $this->item =& $this->getItem($i, $this->params); echo $this->item->id; ?>" class="article-column column<?php echo $z + 1; ?> cols<?php echo $colcount; ?>" >
				<?php $this->item =& $this->getItem($i, $this->params);
				echo $this->loadTemplate('item'); ?>
			</div>
		<?php endfor; ?>
	</div>

	<?php endfor; endif; ?>

	<?php $numlinks = $this->params->def('num_links', 4);
	if ($numlinks && $i < $this->total) : ?>

	<div class="blog-more clearer">
		<?php $this->links = array_slice($this->items, $i - $this->pagination->limitstart, $i - $this->pagination->limitstart + $numlinks);
		echo $this->loadTemplate('links'); ?>
	</div>
	<?php endif; ?>

	<?php if ($this->params->def('show_pagination', 2) == 1  || ($this->params->get('show_pagination') == 2 && $this->pagination->get('pages.total') > 1)) : ?>
	<div id="pagination-wrap">
		<?php if( $this->pagination->get('pages.total') > 1 ) : ?>
		<div class="pagination-links">
			<?php echo $this->pagination->getPagesCounter(); ?>
		</div>
		<?php endif; ?>
		<?php if ($this->params->def('show_pagination_results', 1)) : ?>
			<?php echo $this->pagination->getPagesLinks(); ?>
		<?php endif; ?>
	</div>
	<?php endif; ?>

</div>