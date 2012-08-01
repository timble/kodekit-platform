<?php
/**
 * @version     $Id: paginator.php 4629 2012-05-06 22:11:00Z johanjanssens $
 * @package     Nooku_Components
 * @subpackage  Default
 * @copyright   Copyright (C) 2007 - 2012 Johan Janssens. All rights reserved.
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://www.nooku.org
 */


/**
 * Route Template Helper Class
 *
 * @author      Johan Janssens <johan@nooku.org>
 * @package     Nooku_Components
 * @subpackage  Default
 */
class ComDefaultTemplateHelperRoute extends KTemplateHelperDefault
{
    /**
     * Find a page based on list of needles
     *
     * @param array $needles   An associative array of needles
     * @return
     */
    protected function _findPage($needles)
	{
        $component = JComponentHelper::getComponent('com_'.$this->getIdentifier()->package);
        $items	   = JFactory::getApplication()->getMenu()->getItems('componentid', $component->id);

		$match = null;
        foreach($items as $item)
        {
            $match = $item;

            foreach($needles as $needle)
            {
                foreach($needle as $key => $value)
                {
                    if(!(isset($match->query[$key]) && $match->query[$key] == $value)) {
                        $match = null;
                        break;
                    }
                }

                if(isset($match)) {
                    break(2);
                }
            }
        }

		return $match;
	}
}