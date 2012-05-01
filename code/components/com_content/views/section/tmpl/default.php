<?php // no direct access
defined('_JEXEC') or die('Restricted access');
$cparams =& JComponentHelper::getParams('com_files');
?>

<?php if ($this->params->get('show_page_title', 1)) : ?>
<h1 class="page-header"><?php echo $this->escape($this->params->get('page_title')); ?></h1>
<?php endif; ?>

<?php if ($this->params->get('show_description_image') && $this->section->image) : ?>
	<img src="<?php echo $this->baseurl . '/' . $cparams->get('image_path') . '/'.  $this->section->image;?>" align="<?php echo $this->section->image_position;?>" hspace="6" alt="<?php echo $this->section->image;?>" />
<?php endif; ?>
<?php if ($this->params->get('show_description') && $this->section->description) : ?>
	<?php echo $this->section->description; ?>
<?php endif; ?>

<?php if ($this->params->get('show_categories', 1)) : ?>
	<?php foreach ($this->categories as $category) : ?>
	<?php if (!$this->params->get('show_empty_categories') && !$category->numitems) continue; ?>
		<h2><a href="<?php echo $category->link; ?>" class="category"><?php echo $this->escape($category->title);?></a></h2>
		
		<?php if ($this->params->def('show_category_description', 1) && $category->description) : ?>
		<p><?php echo $category->description; ?></p>
		<?php endif; ?>
		
		<?php if ($this->params->get('show_cat_num_articles')) : ?>
		<p>
			( <?php if ($category->numitems==1) {
			echo $category->numitems ." ". JText::_( 'item' );}
			else {
			echo $category->numitems ." ". JText::_( 'items' );} ?> )
		</p>
		<?php endif; ?>
	<?php endforeach; ?>
<?php endif; ?>