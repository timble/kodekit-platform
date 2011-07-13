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

<h4><?= JText::sprintf( 'Queries logged',  count(KFactory::get('admin::com.debug.event.dispatcher')->getQueries())) ?></h4>
<ol>
<?foreach (KFactory::get('admin::com.debug.event.dispatcher')->getQueries() as $query) : ?>
<li>
	<pre><code class="language-sql"><?= preg_replace('/(FROM|LEFT|INNER|OUTER|WHERE|SET|VALUES|ORDER|GROUP|HAVING|LIMIT|ON|AND)/', '<br />\\0', $query->query); ?></code></pre>
</li>
<? endforeach; ?>
</ol>