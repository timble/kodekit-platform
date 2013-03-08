<?php
/**
 * @category	Nooku
 * @package     Nooku_Server
 * @subpackage  Cache
 * @copyright   Copyright (C) 2011 - 2012 Timble CVBA and Contributors. (http://www.timble.net).
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://www.nooku.org
 */

use Nooku\Framework;

/**
 * Cache Date Helper
 *
 * @author      Johan Janssens <http://nooku.assembla.com/profile/johanjanssens>
 * @category	Nooku
 * @package     Nooku_Server
 * @subpackage  Cache
 */
class ComCacheTemplateHelperDate extends Framework\TemplateHelperDate
{
    /**
     * Returns human readable date.
     * 
     * Set the offset to the system offset as the dates returned by the 
     * cache keys are not GMT. 
     *
     * @param  array   An optional array with configuration options.
     * @return string  Formatted date.
     */
    public function humanize($config = array())
    {
        $config = new Framework\Config($config);
        $config->append(array(
            'gmt_offset'  => date_offset_get(new DateTime)
        ));
        
       return parent::humanize($config);
    }
}
