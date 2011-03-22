<?php
/**
 * @version     $Id$
 * @category	Nooku
 * @package     Nooku_Components
 * @subpackage  Default
 * @copyright   Copyright (C) 2007 - 2010 Johan Janssens. All rights reserved.
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://www.nooku.org
 */


/**
 * Date Helper
.*
 * @author      Johan Janssens <johan@nooku.org>
 * @category    Nooku
 * @package     Nooku_Components
 * @subpackage  Default
 * @uses        KFactory
 */
class ComDefaultTemplateHelperDate extends KTemplateHelperDate
{
    /**
     * Returns formated date according to current local and adds time offset
     *
     * @param   string  A date in ISO 8601 format or a unix time stamp
     * @param   string  format optional format for strftime
     * @returns string  formated date
     * @see     strftime
     */
    public function format($config = array())
    {
        $config = new KConfig($config);
        $config->append(array(
            'format' => JText::_('DATE_FORMAT_LC1'),
            'gmt_offset' => KFactory::get('lib.joomla.config')->getValue('config.offset') * 3600
        ));
        
        return parent::format($config);
    }
    
    /**
     * Returns human readable date.
     *
     * @param  array   An optional array with configuration options.
     * @return string  Formatted date.
     */
    public function humanize($config = array())
    {
        $config = new KConfig($config);
        $config->append(array(
            'gmt_offset' => KFactory::get('lib.joomla.config')->getValue('config.offset') * 3600
        ));
            
        return parent::humanize($config);
    }
}