<? /** $Id: default.php 246 2009-10-12 22:41:50Z johan $ */ ?>
<? defined('KOOWA') or die('Restricted access'); ?>

<div class="vcard">
	<div id="profiles_header">
		<div id="gravatar">
			<span class="photo">
				<img src="http://www.gravatar.com/avatar.php?gravatar_id=<?= md5( strtolower(@$person->email) ); ?>&size=62" alt="Gravatar" />
			</span>
		</div>
		<h1 class="componentheading">
			<span class="fn"><?= @$person->name?></span>
		</h1>
		<h2 id="profiles_position">
			<span class="title"><?= @$person->position?></span>
		</h2>
		<h2 id="profiles_position">
			<span class="org">
				<a href="<?= @route('view=department&id=' . @$person->department_slug) ; ?>" ><?= @$person->department; ?></a>
			</span>
		</h2>
		<h2 id="profiles_position">
			<a href="<?= @route('view=office&id=' . @$person->office_slug) ; ?>" ><?= @$person->office; ?></a>
		</h2>
		<div class="clr"></div>
	</div>
	<div id="profiles_info">
		<span class="tel">
			<span class="type"><?= @text('mobile');?>: </span>
			<span class="value"><?= @$person->mobile?></span>
		</span>
		<a class="email" href="mailto:<?= @$person->email ?>"><?= @$person->email ?></a>
		<span class="bday"><?= JHTML::date(@$person->birthday)?></span>
		<span class="gender_<?= @$person->gender?>"><?= @$person->gender == "1" ? @text('Male') : @text('Female'); ?></span>
		<span class="getvcard">
			<a href="<?=@route('view=person&format=vcard&id='.@$person->slug) ?>" /><?= @text('VCard'); ?></a>
		</span>
	</div>
	<div id="profiles_desc">
		<h2><?= @text('Bio'); ?></h2>
		<span class="note"><?= @$person->bio; ?></span>
	</div>
</div>