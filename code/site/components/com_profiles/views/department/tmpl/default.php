<? /** $Id$ */ ?>
<? defined('KOOWA') or die('Restricted access'); ?>

<style src="media://com_profiles/css/default.css" />

<h1 class="componentheading"><?= $department->title; ?></h1>
<div id="profiles_info">
	<span class="people">
		<a href="<?=@route('view=people&profiles_department_id='.$department->id) ?>">
			<?= $department->people; ?> <?=@text('Employee(s)')?>
		</a>
	</span>
</div>
<div id="profiles_desc">
	<h2><?= @text('Information'); ?></h2>
	<?= $department->description; ?>
</div>
