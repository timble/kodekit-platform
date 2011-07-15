<?php
/**
 * @version     $Id$
 * @category	Nooku
 * @package     Nooku_Components
 * @subpackage  Debug
 * @copyright   Copyright (C) 2011 Timble CVBA and Contributors. (http://www.timble.net).
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://www.nooku.org
 */
defined('KOOWA') or die( 'Restricted access' ); ?>

<h4><?= @text( 'Untranslated Strings' ) ?></h4>
<pre>
<? foreach ($strings as $key => $occurance) : ?>
	<? foreach ( $occurance as $i => $info) : ?>
	<?	
		$class	= @$info['class'];
		$func	= @$info['function'];
		$file	= @$info['file'];
		$line	= @$info['line'];
	?>
	<?= strtoupper( $key )."\t$class::$func()\t[$file:$line]\n"; ?>
	<? endforeach; ?>
<? endforeach; ?>
</pre>