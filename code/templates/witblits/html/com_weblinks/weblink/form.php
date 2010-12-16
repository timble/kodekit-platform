<?php /** $Id$ */
defined( '_JEXEC' ) or die( 'Restricted access' ); ?>

<script language="javascript" type="text/javascript">
function submitbutton(pressbutton)
{
	var form = document.adminForm;
	if (pressbutton == 'cancel') {
		submitform( pressbutton );
		return;
	}

	// do field validation
	if (document.getElementById('jformtitle').value == ""){
		alert( "<?php echo JText::_( 'Weblink item must have a title', true ); ?>" );
	} else if (document.getElementById('jformcatid').value < 1) {
		alert( "<?php echo JText::_( 'You must select a category.', true ); ?>" );
	} else if (document.getElementById('jformurl').value == ""){
		alert( "<?php echo JText::_( 'You must have a url.', true ); ?>" );
	} else {
		submitform( pressbutton );
	}
}
</script>

<?php if ( $this->params->def( 'show_page_title', 1 ) ) : ?>
<h1 class="componentheading clearer"><?php echo $this->escape($this->params->get('page_title')); ?></h1>
<?php endif; ?>
<div id="weblinks-wrap">
<form action="<?php echo $this->action ?>" method="post" name="adminForm" id="adminForm">
<ul id="submit-weblink" class="form">
	<li><label for="jformtitle" class="label"><?php echo JText::_( 'Name' ); ?>:</label>
	<input class="text-input" type="text" id="jformtitle" name="jform[title]" size="50" maxlength="250" value="<?php echo $this->escape($this->weblink->title);?>" /></li>

	<li><label for="jformcatid" class="label"><?php echo JText::_( 'Category' ); ?>:</label>
	<?php echo $this->lists['catid']; ?></li>

	<li><label for="jformurl" class="label"><?php echo JText::_( 'URL' ); ?>:</label>
	<input class="text-input" type="text" id="jformurl" name="jform[url]" value="<?php echo $this->escape($this->weblink->url); ?>" size="50" maxlength="250" /></li>

	<li><label for="jformpublished" class="label"><?php echo JText::_( 'Published' ); ?>:</label>
	<?php echo $this->lists['published']; ?></li>

	<li><label for="jformdescription" class="label"><?php echo JText::_( 'Description' ); ?>:</label>
	<textarea class="text-input" cols="30" rows="6" id="jformdescription" name="jform[description]"><?php echo $this->escape( $this->weblink->description);?></textarea></li>

	<li><label for="jformordering" class="label"><?php echo JText::_( 'Ordering' ); ?>:</label>
	<?php echo $this->lists['ordering']; ?></li>

	<li><button type="button" onclick="submitbutton('save')" class="button save"><?php echo JText::_('Save') ?></button>
	<a href="#" onclick="submitbutton('cancel')"><?php echo JText::_('Cancel') ?></a></li>
</ul>
<input type="hidden" name="jform[id]" value="<?php echo $this->weblink->id; ?>" />
<input type="hidden" name="jform[ordering]" value="<?php echo $this->weblink->ordering; ?>" />
<input type="hidden" name="jform[approved]" value="<?php echo $this->weblink->approved; ?>" />
<input type="hidden" name="option" value="com_weblinks" />
<input type="hidden" name="controller" value="weblink" />
<input type="hidden" name="task" value="" />
<?php echo JHTML::_( 'form.token' ); ?>
</form>
</div>