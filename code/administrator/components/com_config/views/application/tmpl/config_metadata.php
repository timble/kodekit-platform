<?php // no direct access
defined( '_JEXEC' ) or die( 'Restricted access' ); ?>
<fieldset class="adminform">
	<legend><?php echo JText::_( 'Metadata Settings' ); ?></legend>
	<table class="admintable" cellspacing="1">
		<tbody>
		<tr>
			<td valign="top" class="key">
				<span class="editlinktip hasTip" title="<?php echo JText::_( 'Show Title Meta Tag' ); ?>::<?php echo JText::_( 'TIPSHOWTITLEMETATAGITEMS' ); ?>">
					<?php echo JText::_( 'Show Title Meta Tag' ); ?>
				</span>
			</td>
			<td>
				<?php echo $lists['MetaTitle']; ?>
			</td>
		</tr>
		<tr>
			<td valign="top" class="key">
				<span class="editlinktip hasTip" title="<?php echo JText::_( 'Show Author Meta Tag' ); ?>::<?php echo JText::_( 'TIPSHOWAUTHORMETATAGITEMS' ); ?>">
					<?php echo JText::_( 'Show Author Meta Tag' ); ?>
				</span>
			</td>
			<td>
				<?php echo $lists['MetaAuthor']; ?>
			</td>
		</tr>
		</tbody>
	</table>
</fieldset>
