
<div id="attachments-attachments-upload">
	<label><?= @text('Upload attachments') ?></label>
	<ul class="attachments">
	    <li>
	        <?= @helper('com:attachments.template.helper.attachment.upload', array('container' => 'attachments-attachments-upload')) ?>
	    </li>
	</ul>
</div>