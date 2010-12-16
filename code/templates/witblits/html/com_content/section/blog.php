<?php /** $Id$ */
defined( '_JEXEC' ) or die( 'Restricted access' ); ?>

<?php $cparams = JComponentHelper::getParams ('com_media'); ?>

<?php if ($this->params->get('show_page_title')) : ?>
<h1 class="componentheading">
	<?php echo $this->escape($this->params->get('page_title')); ?>
</h1>
<?php endif; ?>

<div class="blog">
	<?php if ($this->params->def('show_description', 1) || $this->params->def('show_description_image', 1)) : ?>
	<div class="desc">
	
		<?php if ($this->params->get('show_description_image') && $this->section->image) : ?>
		<img src="<?php echo $this->baseurl . $cparams->get('image_path').'/'.$this->section->image; ?>" class="img-<?php echo $this->section->image_position; ?>" />
		<?php endif; ?>

		<?php if ($this->params->get('show_description') && $this->section->description) :
			echo $this->section->description;
		endif; ?>

		<?php if ($this->params->get('show_description_image') && $this->section->image) : ?>
		<div class="wrap_image">&nbsp;</div>
		<?php endif; ?>

	</div>
	<?php endif; ?>

	<?php $i = $this->pagination->limitstart;
	$rowcount = $this->params->def('num_leading_articles', 1);
	for ($y = 0; $y < $rowcount && $i < $this->total; $y++, $i++) : ?>
		<div class="leading">
			<?php $this->item =& $this->getItem($i, $this->params);
			echo $this->loadTemplate('item'); ?>
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
		
			<div class="article-row<?php if($colcount > 1) { ?> columns<?php } ?>">
				<?php for ($z = 0; $z < $colcount && $ii < $introcount && $i < $this->total; $z++, $i++, $ii++) : ?>
				
					<?php if($colcount > 1) { ?>
					<div class="article-column column<?php echo $z + 1; ?> cols<?php echo $colcount; ?>" >
					<?php } ?>
					
					    <?php $this->item =& $this->getItem($i, $this->params);
						echo $this->loadTemplate('item'); ?>
						
					<?php if($colcount > 1) { ?>
					</div>
					<?php } ?>


				<?php endfor; ?>
			</div>
		<?php endfor;
	endif; ?>

	<?php $numlinks = $this->params->def('num_links', 4);
	if ($numlinks && $i < $this->total) : ?>
	<div class="blog-more">
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
