<?php
defined('_JEXEC') or die('Restricted access');
$cparams =& JComponentHelper::getParams('com_files');
?>
<?php if ($this->params->get('show_page_title')) : ?>
<h1 class="page-header"><?php echo $this->escape($this->params->get('page_title')); ?></h1>
<?php endif; ?>

<?php if ($this->params->def('show_description', 1) || $this->params->def('show_description_image', 1)) :?>
<p>
	<?php if ($this->params->get('show_description_image') && $this->section->image) : ?>
		<img src="<?php echo $this->baseurl . '/' . $cparams->get('image_path') . '/'. $this->section->image;?>" align="<?php echo $this->section->image_position;?>" hspace="6" alt="" />
	<?php endif; ?>
	<?php if ($this->params->get('show_description') && $this->section->description) : ?>
		<?php echo $this->section->description; ?>
	<?php endif; ?>
</p>
<?php endif; ?>
<?php if ($this->params->def('num_leading_articles', 1)) : ?>
	<?php for ($i = $this->pagination->limitstart; $i < ($this->pagination->limitstart + $this->params->get('num_leading_articles')); $i++) : ?>
		<?php if ($i >= $this->total) : break; endif; ?>
		<?php
			$this->item =& $this->getItem($i, $this->params);
			echo $this->loadTemplate('item');
		?>
	<?php endfor; ?>
<?php else : $i = $this->pagination->limitstart; endif; ?>

<?php
$startIntroArticles = $this->pagination->limitstart + $this->params->get('num_leading_articles');
$numIntroArticles = $startIntroArticles + $this->params->get('num_intro_articles', 4);
?>

<? if (($numIntroArticles != $startIntroArticles) && ($i < $this->total)) : ?>
	<?php for ($y = 0; $y < $this->params->get('num_intro_articles') && $i < $this->total && $i < ($numIntroArticles); $i++, $y ++) :
		$this->item =& $this->getItem($i, $this->params);
		echo $this->loadTemplate('item');
	endfor; ?>
<?php endif; ?>

<?php if ($this->params->def('num_links', 4) && ($i < $this->total)) : ?>
<div class="blog_more">
	<?php
		$this->links = array_splice($this->items, $i - $this->pagination->limitstart);
		echo $this->loadTemplate('links');
	?>
</div>
<?php endif; ?>

<?php if ($this->params->def('show_pagination', 2)) : ?>
	<?php echo $this->pagination->getPagesLinks(); ?>
<?php endif; ?>
<?php if ($this->params->def('show_pagination_results', 1)) : ?>
	<?php echo $this->pagination->getPagesCounter(); ?>
<?php endif; ?>
