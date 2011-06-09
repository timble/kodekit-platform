<?= @helper('listbox.radiolist', array(
	'list'      => array((object) array('title' => 'Uncategorized', 'id' => 0)),
	'name'      => 'category_id',
    'text'      => 'title',
	'selected'  => $article->category_id,
    'translate' => true));
?>

<? foreach($folders->find(array('parent_id' => 0)) as $section) : ?>
	<span class="section"><?= @escape($section->title) ?></span><br />
    <?= @helper('listbox.radiolist', array(
    	'list'     => $folders->find(array('parent_id' => $section->id)),
    	'name'     => 'category_id',
        'text'     => 'title',
    	'selected' => $article->category_id));
    ?>
<? endforeach ?>