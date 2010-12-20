<?php /** $Id$ */
defined( '_JEXEC' ) or die( 'Restricted access' ); ?>

<script language="javascript" type="text/javascript">
	function tableOrdering( order, dir, task ) {
	var form = document.adminForm;

	form.filter_order.value 	= order;
	form.filter_order_Dir.value	= dir;
	document.adminForm.submit( task );
}
</script>
<form action="<?php echo JFilterOutput::ampReplace($this->action); ?>" method="post" name="adminForm">
<?php
echo JText::_('Display Num') .'&nbsp;';
echo $this->pagination->getLimitBox();
?>
<hr class="cut" />
<ul class="weblinks-category">
    <?php foreach ($this->items as $item) : ?>
    <li class="link-item row<?php echo $item->odd + 1; ?>">
    	<?php echo $item->link; ?>
    	<?php if ( $this->params->get( 'show_link_description' ) ) : ?>
    	<p class="description"><?php echo nl2br($this->escape($item->description)); ?></p>
    	<?php endif; ?>
    </li>
    <?php endforeach; ?>
</ul>
<div id="pagination-wrap">
	<div class="pagination-links">
	    <?php echo $this->pagination->getPagesCounter(); ?>
	</div>
	<?php echo $this->pagination->getPagesLinks(); ?>
</div>
<input type="hidden" name="filter_order" value="<?php echo $this->lists['order']; ?>" />
<input type="hidden" name="filter_order_Dir" value="" />
</form>