<? /** $Id$ */ ?>
<? @style(@$mediaurl.'/com_beer/css/default.css'); ?>
<div id="beer_header">
	<div id="gravatar"><img src="http://www.gravatar.com/avatar.php?gravatar_id=<?= md5( strtolower(@$person->email) ); ?>&size=48" alt="Gravatar" /></div> 
	<h1 class="componentheading"><?= @$person->firstname . ' ' .  @$person->middlename . ' ' . @$person->lastname; ?></h1>
	<h2 id="beer_position"><?= @$person->position; ?></h2>
	<div class="clr"></div>
</div>
<div id="beer_info">
	<span class="mobile"><?= @$person->mobile; ?></spam>
	<span class="email"><?= @$person->email; ?></span>
	<span class="dob"><?= @$person->dob; ?></span>
	<span class="gender_<?= @$person->gender; ?>"><?= @$person->gender == "1" ? @text('Male') : @text('Female'); ?></span>
	<span class="vcard"><?= @text('VCard'); ?></span>
	</div>
<div id="beer_desc">
	<h2><?= @text('Bio'); ?></h2>
	<?= @$person->bio; ?>
</div>