<? /** $Id$ */ ?>
<? defined('KOOWA') or die('Restricted access'); ?>
<? @style(@$mediaurl.'/com_beer/css/default.css'); ?>
<? @script(@$mediaurl.'/plg_koowa/js/koowa.js'); ?>

<div id="beer_header">
	<div id="gravatar"><img src="http://www.gravatar.com/avatar.php?gravatar_id=<?= md5( strtolower(@$person->email) ); ?>&size=62" alt="Gravatar" /></div>
	<h1 class="componentheading"><?= @$person->name?></h1>
	<h2 id="beer_position"><?= @$person->position?></h2>
	<h2 id="beer_position"><a href="<?= @route('index.php?option=com_beer&amp;view=department&id=' . @$person->department_slug) ; ?>" ><?= @$person->department; ?></a></h2>
	<h2 id="beer_position"><a href="<?= @route('index.php?option=com_beer&amp;view=office&id=' . @$person->office_slug) ; ?>" ><?= @$person->office; ?></a></h2>
	<div class="clr"></div>
</div>
<div id="beer_info">
	<span class="mobile"><?= @$person->mobile?></spam>
	<span class="email"><?= JHTML::_('email.cloak',@$person->email)?></span>
	<span class="dob"><?= @$person->birthday?></span>
	<span class="gender_<?= @$person->gender?>"><?= @$person->gender == "1" ? @text('Male') : @text('Female'); ?></span>
	<span class="vcard"><a href="<?=@route('option=com_beer&view=person&format=vcard&id='.@$person->slug) ?>" /><?= @text('VCard'); ?></a></span>
	</div>
<div id="beer_desc">
	<h2><?= @text('Bio'); ?></h2>
	<?= @$person->bio; ?>
</div>