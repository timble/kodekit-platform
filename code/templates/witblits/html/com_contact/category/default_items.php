<?php /** $Id$ */
defined( '_JEXEC' ) or die( 'Restricted access' ); ?>

<?php foreach($this->items as $item) : ?>
<tr class="row<?php echo $item->odd + 1; ?>">
	<td class="tbl-number">
		<?php echo $item->count +1; ?>
	</td>
	<td class="tbl-name">
		<a href="<?php echo $item->link; ?>" class="category<?php echo $this->escape($this->params->get('pageclass_sfx')); ?>">
			<?php echo $item->name; ?></a>
	</td>
	<?php if ( $this->params->get( 'show_position' ) ) : ?>
	<td class="tbl-position">
		<?php echo $this->escape($item->con_position); ?>
	</td>
	<?php endif; ?>
	<?php if ( $this->params->get( 'show_email' ) ) : ?>
	<td class="tbl-email">
		<?php echo $item->email_to; ?>
	</td>
	<?php endif; ?>
	<?php if ( $this->params->get( 'show_telephone' ) ) : ?>
	<td class="tbl-tel">
		<?php echo $this->escape($item->telephone); ?>
	</td>
	<?php endif; ?>
	<?php if ( $this->params->get( 'show_mobile' ) ) : ?>
	<td class="tbl-mobile">
		<?php echo $this->escape($item->mobile); ?>
	</td>
	<?php endif; ?>
	<?php if ( $this->params->get( 'show_fax' ) ) : ?>
	<td class="tbl-fax">
		<?php echo $this->escape($item->fax); ?>
	</td>
	<?php endif; ?>
</tr>
<?php endforeach; ?>