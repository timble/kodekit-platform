<?
/**
 * Kodekit Component - http://www.timble.net/kodekit
 *
 * @copyright	Copyright (C) 2011 - 2016 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license		MPL v2.0 <https://www.mozilla.org/en-US/MPL/2.0>
 * @link		https://github.com/timble/kodekit-deugger for the canonical source repository
 */
?>

<h4><?= translate( 'Untranslated Strings' ) ?></h4>
<pre>
<? foreach ($strings as $key => $occurance) : ?>
	<? foreach ( $occurance as $i => $info) : ?>
	<?
		$class	= $info['class'];
		$func	= $info['function'];
		$file	= $info['file'];
		$line	= $info['line'];
	?>
	<?= strtoupper( $key )."\t$class::$func()\t[$file:$line]\n"; ?>
	<? endforeach; ?>
<? endforeach; ?>
</pre>