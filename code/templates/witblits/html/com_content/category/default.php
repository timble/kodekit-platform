<?php /** $Id$ */
defined( '_JEXEC' ) or die( 'Restricted access' ); ?>

<?php $cparams = JComponentHelper::getParams ('com_media'); ?>

<?php if ($this->params->get('show_page_title',1)) : ?>
<h1 class="componentheading clearer">
<?php echo $this->escape($this->params->get('page_title')); ?>
</h1>
<?php endif; ?>
<?php if ($this->params->def('show_description', 1) || $this->params->def('show_description_image', 1)) : ?>
<div class="desc">
<?php if ($this->params->get('show_description_image') && $this->category->image) : ?>
<img src="<?php echo $this->baseurl . $cparams->get('image_path').'/'.$this->category->image; ?>" class="image_<?php echo $this->category->image_position; ?>" />
<?php endif; ?>
<?php if ($this->params->get('show_description') && $this->category->description) :
echo $this->category->description;
endif; ?>
<?php if ($this->params->get('show_description_image') && $this->category->image) : ?>
<div class="wrap_image">&nbsp;</div>
<?php endif; ?>
</div>
<?php endif; ?>
<?php $this->items =& $this->getItems();
echo $this->loadTemplate('items'); ?>
<?php if ($this->access->canEdit || $this->access->canEditOwn) : ?>
<?php echo JHTML::_('icon.create', $this->category, $this->params, $this->access); ?>
<?php endif; ?>