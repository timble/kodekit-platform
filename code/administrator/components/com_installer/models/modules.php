<?php
/**
 * @version     $Id$
 * @category    Nooku
 * @package     Nooku_Server
 * @subpackage  Installer
 * @copyright   Copyright (C) 2011 Timble CVBA and Contributors. (http://www.timble.net).
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://www.nooku.org
 */

/**
 * Modules Model Class
 *
 * @author      Stian Didriksen <http://nooku.assembla.com/profile/stiandidriksen>
 * @category    Nooku
 * @package     Nooku_Server
 * @subpackage  Installer
 */
class ComInstallerModelModules extends ComExtensionsModelModules
{
    public function __construct(KConfig $config)
    {
        parent::__construct($config);

        $filter = 'com://admin/installer.filter.application';
        $this->_state
            ->insert('application', $filter, array('site', 'administrator'))   
            ->insert('installed', 'boolean', true);
    }
    
    /**
     * Initializes the config for the object
     *
     * Customizing the table identifier to the com_extensions one
     *
     * @param   object  An optional KConfig object with configuration options
     * @return  void
     */
    protected function _initialize(KConfig $config)
    {
        $identifier = clone $this->getIdentifier();
        $identifier->path    = array('database', 'table');
        $identifier->package = 'extensions';
        
        $config->append(array(
            'table' => $identifier,
        ));

        parent::_initialize($config);
    }
}