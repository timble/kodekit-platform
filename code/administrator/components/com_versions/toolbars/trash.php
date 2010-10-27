<?php 
/**
 * @version		$Id$
 * @category	Koowa
 * @package     Koowa_Components
 * @subpackage  Versions
 * @copyright	Copyright (C) 2010 Timble CVBA and Contributors. (http://www.timble.net)
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		http://www.nooku.org
 */

/**
 * Trash toolbar
.*
 * @author      Johan Janssens <johan@timble.net>
 * @category	Koowa
 * @package     Koowa_Components
 * @subpackage  Versions
 */
class ComVersionsToolbarTrash extends KToolbarAbstract
{
	public function __construct(KConfig $config)
	{
        parent::__construct($config);
		
		$this->append(KFactory::tmp('admin::com.versions.toolbar.button.delete'))
			 ->append(KFactory::tmp('admin::com.versions.toolbar.button.restore'));
	}
}