<?php defined('_JEXEC') or die('Restricted access'); ?>

<div id="left">
	<div id="media-tree_tree"></div>
	<?php echo $this->loadTemplate('folders'); ?>
</div>
<div id="right">
	<form action="index.php?option=com_media&amp;task=folder.create" name="folderForm" id="folderForm" method="post">
	    <div id="folderview">
	        <div class="path">
	             <input class="inputbox" type="text" id="folderpath" readonly="readonly" />/
	             <input class="inputbox" type="text" id="foldername" name="foldername"  />
	             <input class="update-folder" type="hidden" name="folderbase" id="folderbase" value="<?php echo $this->state->folder; ?>" />
	             <button type="submit"><?php echo JText::_( 'Create Folder' ); ?></button>
	         </div>
	         <div class="view">
	             <iframe src="index.php?option=com_media&amp;view=mediaList&amp;tmpl=component&amp;folder=<?php echo $this->state->folder;?>" id="folderframe" name="folderframe" width="100%" marginwidth="0" marginheight="0" scrolling="auto" frameborder="0"></iframe>
	         </div>
	    </div>
		<?php echo JHTML::_( 'form.token' ); ?>
	</form>

	<!-- File Upload Form -->
	<?php $canUpload= ($this->user->authorize('com_media', 'upload')); ?> 	
	<?php if ($canUpload) : ?>
		<form action="<?php echo JURI::base(); ?>index.php?option=com_media&amp;task=file.upload&amp;tmpl=component&amp;<?php echo $this->session->getName().'='.$this->session->getId(); ?>&amp;<?php echo JUtility::getToken();?>=1" id="uploadForm" method="post" enctype="multipart/form-data">
			<div id="upload">
				<fieldset id="upload-noflash" class="actions">
					<input type="file" id="file-upload" name="Filedata" />
					<input type="submit" id="file-upload-submit" value="<?php echo JText::_('Start Upload'); ?>"/>
					<span id="upload-clear"></span>
				</fieldset>
				<div id="upload-flash" class="hide">
				<ul>
					<li><a href="#" id="upload-browse"><?php echo JText::_('Browse Files'); ?></a></li>
					<li><a href="#" id="upload-clear"><?php echo JText::_('Clear List'); ?></a></li>
					<li><a href="#" id="upload-start"><?php echo JText::_('Start Upload'); ?></a></li>
					<li><?php echo JText::_( 'Upload File' ); ?> [ <?php echo JText::_( 'Max' ); ?>&nbsp;<?php echo ($this->config->get('upload_maxsize') / 1000000); ?>M ]</li>
				</ul>
				<div id="upload-progress">
					<p class="overall-title"></p>
					<?php echo JHTML::_('image','media/com_media/images/bar.gif', JText::_('Overall Progress'), array('class' => 'progress overall-progress'), true); ?>			
				</div>	
			</div>
				<ul class="upload-queue" id="upload-queue">
					<li style="display: none" />
				</ul>
			</div>
			<input type="hidden" name="return-url" value="<?php echo base64_encode('index.php?option=com_media'); ?>" />
		</form>
	<?php endif; ?>

    <?php if ($this->require_ftp): ?>
    <form action="index.php?option=com_media&amp;task=ftpValidate" name="ftpForm" id="ftpForm" method="post">
        <?php echo JText::_('DESCFTP'); ?>
        <table class="adminform nospace">
            <tbody>
                <tr>
                    <td width="120">
                        <label for="username"><?php echo JText::_('Username'); ?>:</label>
                    </td>
                    <td>
                        <input type="text" id="username" name="username" class="input_box" size="70" value="" />
                    </td>
                </tr>
                <tr>
                    <td width="120">
                        <label for="password"><?php echo JText::_('Password'); ?>:</label>
                    </td>
                    <td>
                        <input type="password" id="password" name="password" class="input_box" size="70" value="" />
                    </td>
                </tr>
            </tbody>
        </table>
    </form>
    <?php endif; ?>

    <form action="index.php?option=com_media" name="adminForm" id="mediamanager-form" method="post" enctype="multipart/form-data" >
        <input type="hidden" name="task" value="" />
        <input type="hidden" name="cb1" id="cb1" value="0" />
        <input class="update-folder" type="hidden" name="folder" id="folder" value="<?php echo $this->state->folder; ?>" />
    </form>    
</div>
