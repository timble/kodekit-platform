<?php
/**
 * @version     $Id: html.php 628 2011-03-20 01:49:53Z johanjanssens $
 * @category	Nooku
 * @package     Nooku_Server
 * @subpackage  Cache
 * @copyright   Copyright (C) 2011 Timble CVBA and Contributors. (http://www.timble.net).
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://www.nooku.org
 */

/**
 * Cache Groups Html View
 *
 * @author      Johan Janssens <http://nooku.assembla.com/profile/johanjanssens>
 * @category	Nooku
 * @package     Nooku_Server
 * @subpackage  Cache
 */
class ComCacheViewHtml extends ComDefaultViewHtml
{
    public function __construct(KConfig $config)
	{
		$config->views = array(
			'groups' => JText::_('Groups'),
			'keys' 	 => JText::_('Keys'),
        );
		
		parent::__construct($config);
	}
}