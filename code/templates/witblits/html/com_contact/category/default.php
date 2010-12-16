<?php /** $Id$ */
defined( '_JEXEC' ) or die( 'Restricted access' ); ?>

<?php 
$cparams =& JComponentHelper::getParams('com_media');
if ( $this->params->get( 'show_page_title', 1 ) ) : ?>
<h1 class="componentheading clearer">
    <?php echo $this->escape($this->params->get('page_title')); ?>
</h1>
<?php endif; ?>
<div id="contact-table">
<?php if ($this->category->image || $this->category->description) : ?>
	<div class="desc">
	<?php if ($this->params->get('image') != -1 && $this->params->get('image') != '') : ?>
		<img src="<?php echo $this->baseurl .'/'. 'images/stories' . '/'. $this->params->get('image'); ?>" align="<?php echo $this->params->get('image_align'); ?>" hspace="6" alt="<?php echo JText::_( 'Contacts' ); ?>" />
	<?php elseif ($this->category->image) : ?>
		<img src="<?php echo $this->baseurl .'/'. 'images/stories' . '/'. $this->category->image; ?>" align="<?php echo $this->category->image_position; ?>" hspace="6" alt="<?php echo JText::_( 'Contacts' ); ?>" />
	<?php endif; ?>
	<?php echo $this->category->description; ?>
	</div>
<?php endif; ?>
<script language="javascript" type="text/javascript">
	function tableOrdering( order, dir, task ) {
	var form = document.adminForm;

	form.filter_order.value 	= order;
	form.filter_order_Dir.value	= dir;
	document.adminForm.submit( task );
}
</script>
<form action="<?php echo $this->action; ?>" method="post" name="adminForm">
    <?php if ($this->params->get('show_limit')) : ?>
    <div class="table-limit">
    	<?php echo JText::_('Display Num') .'&nbsp;';
    	echo $this->pagination->getLimitBox(); ?>
    </div>
    <?php endif; ?>
    <table width="100%" border="0" cellspacing="0" cellpadding="0" align="center">
    	<?php if ($this->params->get( 'show_headings' )) : ?>
    	<thead>
    		<tr>
    			<th class="tbl-number">
    			<?php echo JText::_('Num'); ?>
    			</th>
    			<th class="tbl-name">
    				<?php echo JHTML::_('grid.sort',  'Name', 'cd.name', $this->lists['order_Dir'], $this->lists['order'] ); ?>
    			</th>
    			<?php if ( $this->params->get( 'show_position' ) ) : ?>
    			<th class="tbl-position">
    				<?php echo JHTML::_('grid.sort',  'Position', 'cd.con_position', $this->lists['order_Dir'], $this->lists['order'] ); ?>
    			</th>
    			<?php endif; if ( $this->params->get( 'show_email' ) ) : ?>
    			<th class="tbl-email">
    				<?php echo JText::_( 'Email' ); ?>
    			</th>
    			<?php endif; if ( $this->params->get( 'show_telephone' ) ) : ?>
    			<th class="tbl-tel">
    				<?php echo JText::_( 'Phone' ); ?>
    			</th>
    			<?php endif; if ( $this->params->get( 'show_mobile' ) ) : ?>
    			<th class="tbl-mobile">
    				<?php echo JText::_( 'Mobile' ); ?>
    			</th>
    			<?php endif; if ( $this->params->get( 'show_fax' ) ) : ?>
    			<th class="tbl-fax">
    				<?php echo JText::_( 'Fax' ); ?>
    			</th>
    			<?php endif; ?>
    		</tr>
    	</thead>
    	<?php endif; ?>
    	<tfoot>
    		<tr>
    			<td colspan="6">
    				<?php echo $this->pagination->getPagesLinks(); ?>
    			</td>
    		</tr>
    		<tr>
    			<td colspan="6" align="right">
    				<?php echo $this->pagination->getPagesCounter(); ?>
    			</td>
    		</tr>
    	</tfoot>
    	<tbody>
    	    <?php echo $this->loadTemplate('items'); ?>
        </tbody>
    </table>
    <input type="hidden" name="option" value="com_contact" />
    <input type="hidden" name="catid" value="<?php echo $this->category->id;?>" />
    <input type="hidden" name="filter_order" value="<?php echo $this->lists['order']; ?>" />
    <input type="hidden" name="filter_order_Dir" value="" />
</form>
</div>