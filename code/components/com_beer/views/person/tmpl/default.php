<? /** $Id$ */ ?>
<? defined('KOOWA') or die('Restricted access'); ?>
<? @style(@$mediaurl.'/com_beer/css/default.css'); ?>
<? @script(@$mediaurl.'/plg_koowa/js/koowa.js'); ?>
<div class="vcard">
	<div id="beer_header">
		<div id="gravatar"><span class="photo"><img src="http://www.gravatar.com/avatar.php?gravatar_id=<?= md5( strtolower(@$person->email) ); ?>&size=62" alt="Gravatar" /></span></div>
		<h1 class="componentheading"><span class="fn"><?= @$person->name?></span></h1>
		<h2 id="beer_position"><span class="title"><?= @$person->position?></span></h2>
		<h2 id="beer_position"><span class="org"><a href="<?= @route('view=department&id=' . @$person->department_slug) ; ?>" ><?= @$person->department; ?></a></span></h2>
		<h2 id="beer_position"><a href="<?= @route('view=office&id=' . @$person->office_slug) ; ?>" ><?= @$person->office; ?></a></h2>
		<div class="clr"></div>
	</div>
	<div id="beer_info">
		<span class="tel"><span class="type"><?= @text('mobile');?>: </span><span class="value"><?= @$person->mobile?></span></span>
		<span class="email"><?= @$person->email ? JHTML::_('email.cloak',@$person->email) : ''?></span>
		<span class="bday"><?= JHTML::date(@$person->birthday)?></span>
		<span class="gender_<?= @$person->gender?>"><?= @$person->gender == "1" ? @text('Male') : @text('Female'); ?></span>
		<span class="getvcard"><a href="<?=@route('view=person&format=vcard&id='.@$person->slug) ?>" /><?= @text('VCard'); ?></a></span>
		<? if(@$user->id == @$person->user_id) : ?>
			<span class="edituser"><a href="<?=@route('view=person&layout=form&id='.@$person->slug) ?>" alt="<?= @text('Edit Profile'); ?>"/><?= @text('Edit Profile'); ?></a></span>
		<? endif ; ?>
	</div>
	<div id="beer_desc">
		<h2><?= @text('Bio'); ?></h2>
		<span class="note"><?= @$person->bio; ?></span>
	</div>
</div>