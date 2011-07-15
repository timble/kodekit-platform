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

<ol>
<?foreach ($queries as $query) : ?>
<li>
	<span>[<?= sprintf('%.3f', $query->time*1000).' msec' ?>]</span>
	<pre><?= preg_replace('/(FROM|LEFT|INNER|OUTER|WHERE|SET|VALUES|ORDER|GROUP|HAVING|LIMIT|ON|AND)/', '<br />\\0', $query->query); ?></pre>
</li>
<? endforeach; ?>
</ol>