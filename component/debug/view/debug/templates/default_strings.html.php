<?
/**
 * Nooku Framework - http://www.nooku.org
 *
 * @copyright	Copyright (C) 2011 - 2013 Timble CVBA and Contributors. (http://www.timble.net)
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		git://git.assembla.com/nooku-framework.git
 */
?>

<h4><?= @text( 'Untranslated Strings' ) ?></h4>
<pre>
<? foreach ($strings as $key => $occurance) : ?>
	<? foreach ( $occurance as $i => $info) : ?>
	<?	
		$class	= $info['class'];
		$func	= $info['function'];
		$file	= $info['file'];
		$line	= $info['line'];
	?>
	<?= strtoupper( $key )."\t$class::$func()\t[$file:$line]".PHP_EOL; ?>
	<? endforeach; ?>
<? endforeach; ?>
</pre>