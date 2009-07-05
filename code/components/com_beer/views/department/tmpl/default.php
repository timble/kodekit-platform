<? /** $Id$ */ ?>
<? defined('KOOWA') or die('Restricted access'); ?>
<? @style(@$mediaurl.'/com_beer/css/default.css'); ?>

<h1 class="componentheading"><?= @$department->title; ?></h1>
<div id="beer_info">
	<span class="people">
		<a href="<?=@route('option=com_beer&view=people&beer_department_id='.@$department->id) ?>">
			<?= @$department->people; ?> <?=@text('Employee(s)')?>
		</a>
	</span>
</div>
<div id="beer_desc">
	<h2><?= @text('Information'); ?></h2>
	<?= @$department->description; ?>
</div>
