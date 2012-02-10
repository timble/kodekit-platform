<?php
/**
 * @version     $Id$
 * @category	Nooku
 * @package     Nooku_Components
 * @subpackage  Logs
 * @copyright   Copyright (C) 2011 - 2012 Timble CVBA and Contributors. (http://www.timble.net).
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://www.nooku.org
 */

defined('KOOWA') or die( 'Restricted access' ); ?>

<h3><?=@text( 'Components' )?></h3>

<?php if (count($packages)): ?>
<ul>
	<?php foreach ($packages as $package): ?>
		<?php if ($package->id == $state->package): ?>
			<li class="active">
		<?php else: ?> <li> <?php endif ?>
			<a href="<?=@route('view=logs&package='.$package->id)?>"><?=ucfirst($package->package)?></a>
		</li>	
	<?php endforeach ?>
</ul>	
<?php endif ?>
