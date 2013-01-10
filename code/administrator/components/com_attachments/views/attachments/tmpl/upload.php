
<div id="attachments-attachments-upload">
	<label><?= @text('Attachments') ?></label>
	<ul class="attachments">
	    <li>
	        <?= @helper('com://admin/attachments.template.helper.attachment.upload', array('holder' => 'attachments-attachments-upload')) ?>
	    </li>
	</ul>
</div>