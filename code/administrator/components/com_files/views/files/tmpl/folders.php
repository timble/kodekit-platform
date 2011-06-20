
<ul <?= isset($id) && $id === false ? '' : 'id="files-tree-html"'; ?>>
<? foreach($folders as $folder): ?>
	<li>
		<a href="#!/<?= $folder->path; ?>" title="<?= $folder->path; ?>">
			<span class="icon"></span>
			<!--id:<?= $folder->path; ?>-->
			<?= $folder->name; ?>
		</a>
	<? if (count($folder->children)): $clone = clone $this; ?>
		<?= $clone->loadIdentifier('folders', array('folders' => $folder->children, 'id' => false)); ?>
	<? endif; ?>
	</li>
<? endforeach; ?>
</ul>