
<?= @helper('behavior.modal') ?>

<? if (count($folders)): ?>
<h3><?= @text('Folders')?></h3>
<? foreach ($folders as $folder): ?>
<p>
	<a href="<?= @route('view=gallery&container='.$state->container.'&folder='.$folder->path); ?>"><?= $folder->path ?></a>
</p>
<? endforeach; ?>
<? endif; ?>


<? if (count($images)): ?>
<h3><?= @text('Images')?></h3>
<? foreach ($images as $image): 
	if (isset($thumbnails[$image->name])) {
		$thumb = $thumbnails[$image->name]->thumbnail;
	}
	else {
		$thumb = $path.'/'.$image->path;
	}
?> 
	<a href="<?= $path.'/'.$image->path; ?>" class="modal">
		<img src="<?= $thumb ?>" style="max-width: 60px; max-height: 60px; float: left; margin: 5px;" />
	</a>
<? endforeach; ?>
<? endif; ?>