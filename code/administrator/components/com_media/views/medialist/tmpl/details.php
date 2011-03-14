<?php defined('_JEXEC') or die('Restricted access'); ?>
<form action="index.php?option=com_media&amp;tmpl=component&amp;folder=<?php echo $this->state->folder; ?>" method="post" id="mediamanager-form" name="mediamanager-form">
	<table class="adminlist" width="100%" cellspacing="0">
	<thead>
		<tr>
			<th width="5"></th>
			<th><?php echo JText::_( 'Name' ); ?></th>
			<th><?php echo JText::_( 'Dimensions' ); ?></th>
			<th><?php echo JText::_( 'Size' ); ?></th>
		</tr>
	</thead>
	<tbody>
		<?php for ($i=0,$n=count($this->folders); $i<$n; $i++) :
			$this->setFolder($i);
			echo $this->loadTemplate('folder');
		endfor; ?>

		<?php for ($i=0,$n=count($this->documents); $i<$n; $i++) :
			$this->setDoc($i);
			echo $this->loadTemplate('doc');
		endfor; ?>

		<?php for ($i=0,$n=count($this->images); $i<$n; $i++) :
			$this->setImage($i);
			echo $this->loadTemplate('img');
		endfor; ?>
	</tbody>
	</table>
	<input type="hidden" name="task" value="list" />
	<input type="hidden" name="username" value="" />
	<input type="hidden" name="password" value="" />
	<?php echo JHTML::_( 'form.token' ); ?>
</form>
