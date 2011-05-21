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
 * Preferences Toolbar Button
 *
 * @author      Johan Janssens <johan@nooku.org>
 * @category    Nooku
 * @package     Nooku_Components
 * @subpackage  Default
 */
class ComDefaultToolbarButtonPreferences extends KToolbarButtonGet
{    
 	/**
     * Initializes the options for the object
     *
     * Must include @helper('behavior.modal') in view, to load the modal behavior
     *
     * Called from {@link __construct()} as a first step of object instantiation.
     *
     * @param   object  An optional KConfig object with configuration options
     * @return  void
     */
    protected function _initialize(KConfig $config)
    {
        $config->append(array(
            'width'   => '640',
            'height'  => '480',
        ))->append(array(
            'attribs' => array(
                'class' => 'toolbar modal',
                'href'  => 'index.php?option=com_config&controller=component&component=com_'.$this->_identifier->package,
                'rel'   => '{handler: \'iframe\', size: {x: '.$config->width.', y: '.$config->height.'}}'
            )
        ));

        parent::_initialize($config);
    }
}