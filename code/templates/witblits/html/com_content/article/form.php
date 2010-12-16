<?php /** $Id$ */
defined( '_JEXEC' ) or die( 'Restricted access' ); ?>

<?php
$document = Jfactory::getDocument();  
$document->addStyleSheet('templates/witblits/css/frontendedit.css');
JHTML::_('behavior.mootools');
?>
<script language="javascript" type="text/javascript">
<!--
function setgood() {
	// TODO: Put setGood back
	return true;
}

var sectioncategories = new Array;
<?php
$i = 0;
foreach ($this->lists['sectioncategories'] as $k=>$items) {
	foreach ($items as $v) {
		echo "sectioncategories[".$i++."] = new Array( '$k','".addslashes( $v->id )."','".addslashes( $v->title )."' );\n\t\t";
	}
}
?>
function submitbutton(pressbutton) {
	var form = document.adminForm;
	if (pressbutton == 'cancel') {
		submitform( pressbutton );
		return;
	}
	try {
		form.onsubmit();
	} catch(e) {
		alert(e);
	}

	// do field validation
	var text = <?php echo $this->editor->getContent( 'text' ); ?>
	if (form.title.value == '') {
		return alert ( "<?php echo JText::_( 'Article must have a title', true ); ?>" );
	} else if (text == '') {
		return alert ( "<?php echo JText::_( 'Article must have some text', true ); ?>");
	} else if (parseInt('<?php echo $this->article->sectionid;?>')) {
		// for articles
		if (form.catid && getSelectedValue('adminForm','catid') < 1) {
			return alert ( "<?php echo JText::_( 'Please select a category', true ); ?>" );
		}
	}
	<?php echo $this->editor->save( 'text' ); ?>
	submitform(pressbutton);
}
//-->
</script>
<?php if ($this->params->get('show_page_title', 1)) : ?>
<h1 class="componentheading"><?php echo $this->escape($this->params->get('page_title')); ?></h1>
<?php endif; ?>
<form action="<?php echo $this->action ?>" method="post" name="adminForm" onSubmit="setgood();" id="edit-content">
<h2><?php echo JText::_('Editor'); ?></h2>
<fieldset id="editor">
    <div id="editor-top">
        <input class="editor-title" type="text" id="title" name="title" size="50" maxlength="100" value="<?php echo $this->escape($this->article->title); ?>" />
        <input class="editor-alias" type="hidden" id="alias" name="alias" value="<?php echo $this->escape($this->article->alias); ?>" />
        <button type="button" class="button save" onclick="submitbutton('save')"><?php echo JText::_('Save') ?></button>
        <a href="#" onclick="submitbutton('cancel')"><?php echo JText::_('Cancel') ?></a>
    </div>
    <?php echo $this->editor->display('text', $this->article->text, '100%', '400', '70', '15'); ?>
</fieldset>
<h2><?php echo JText::_('Publishing'); ?></h2>
<fieldset id="publishing">    
    <ul>
        <li><label for="sectionid" class="label"><?php echo JText::_( 'Section' ); ?>:</label>
        <?php echo $this->lists['sectionid']; ?></li>
        <li><label for="catid" class="label"><?php echo JText::_( 'Category' ); ?>:</label>
        <?php echo $this->lists['catid']; ?></li>
        <?php if ($this->user->authorize('com_content', 'publish', 'content', 'all')) : ?>
        <li><label for="state" class="label"><?php echo JText::_( 'Published' ); ?>:</label>
        <?php echo $this->lists['state']; ?></li>
        <?php endif; ?>
        <li><label for="frontpage" class="label"><?php echo JText::_( 'Show on Front Page' ); ?>:</label>
        <?php echo $this->lists['frontpage']; ?></li>
        <li><label for="created_by_alias" class="label"><?php echo JText::_( 'Author Alias' ); ?>:</label>
        <input type="text" id="created_by_alias" name="created_by_alias" size="50" maxlength="100" value="<?php echo $this->article->created_by_alias; ?>" /></li>
        <li><label for="publish_up" class="label"><?php echo JText::_( 'Start Publishing' ); ?>:</label>
        <?php echo JHTML::_('calendar', $this->article->publish_up, 'publish_up', 'publish_up', '%Y-%m-%d %H:%M:%S', array('size'=>'25',  'maxlength'=>'19')); ?></li>
        <li><label for="publish_down" class="label"><?php echo JText::_( 'Finish Publishing' ); ?>:</label>
        <?php echo JHTML::_('calendar', $this->article->publish_down, 'publish_down', 'publish_down', '%Y-%m-%d %H:%M:%S', array('size'=>'25',  'maxlength'=>'19')); ?></li>
        <li><label for="access" class="label"><?php echo JText::_( 'Access Level' ); ?>:</label>
        <?php echo $this->lists['access']; ?></li>
        <li class="last"><label for="ordering" class="label"><?php echo JText::_( 'Ordering' ); ?>:</label>
        <?php echo $this->lists['ordering']; ?></li>
    </ul>
</fieldset>
<h2><?php echo JText::_('Metadata'); ?></h2>
<fieldset id="meta">    
    <label for="metadesc" class="block"><?php echo JText::_( 'Description' ); ?>:</label>
    <textarea rows="1" id="metadesc" name="metadesc"><?php echo str_replace('&','&amp;',$this->article->metadesc); ?></textarea>
    <label for="metakey" class="block"><?php echo JText::_( 'Keywords' ); ?>:</label>
    <textarea rows="1" id="metakey" name="metakey"><?php echo str_replace('&','&amp;',$this->article->metakey); ?></textarea>
</fieldset>
<input type="hidden" name="option" value="com_content" />
<input type="hidden" name="id" value="<?php echo $this->article->id; ?>" />
<input type="hidden" name="version" value="<?php echo $this->article->version; ?>" />
<input type="hidden" name="created_by" value="<?php echo $this->article->created_by; ?>" />
<input type="hidden" name="referer" value="<?php echo @$_SERVER['HTTP_REFERER']; ?>" />
<?php echo JHTML::_( 'form.token' ); ?>
<input type="hidden" name="task" value="" />
</form>
<?php echo JHTML::_('behavior.keepalive'); ?>