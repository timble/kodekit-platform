<?php
/**
 * Nooku Framework - http://www.nooku.org
 *
 * @copyright	Copyright (C) 2011 - 2013 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		git://git.assembla.com/nooku-framework.git for the canonical source repository
 */

use Nooku\Library;

/**
 * Date Template Helper
 *
 * @author  Johan Janssens <http://nooku.assembla.com/profile/johanjanssens>
 * @package Component\Cache
 */
class CacheTemplateHelperDate extends Library\TemplateHelperDate
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
        $config = new Library\ObjectConfig($config);
        $config->append(array(
            'gmt_offset'  => date_offset_get(new DateTime)
        ));
        
       return parent::humanize($config);
    }
}
