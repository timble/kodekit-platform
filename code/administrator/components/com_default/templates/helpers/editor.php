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
 * Editor Helper
.*
 * @author      Johan Janssens <johan@nooku.org>
 * @category    Nooku
 * @package     Nooku_Components
 * @subpackage  Default
 * @uses        KFactory
 * @uses        KConfig
 */
class ComDefaultTemplateHelperEditor extends KTemplateHelperAbstract
{
    /**
     * Generates an HTML editor
     *
     * @param   array   An optional array with configuration options
     * @return  string  Html
     */
    public function display($config = array())
    {
        $config = new KConfig($config);
        $config->append(array(
            'editor'    => null,
            'name'      => 'description',
            'width'     => '100%',
            'height'    => '500',
            'cols'      => '75',
            'rows'      => '20',
            'buttons'   => true,
            'options'   => array()
        ));

        $editor  = KFactory::get('lib.joomla.editor', array($config->editor));
        $options = KConfig::toData($config->options);

        return $editor->display($config->name, $config->{$config->name}, $config->width, $config->height, $config->cols, $config->rows, $config->buttons, $options);
    }
}