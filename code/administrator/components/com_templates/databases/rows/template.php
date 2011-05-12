<?php
/**
 * @version     $Id$
 * @category    Nooku
 * @package     Nooku_Server
 * @subpackage  Templates
 * @copyright   Copyright (C) 2011 Timble CVBA and Contributors. (http://www.timble.net).
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://www.nooku.org
 */

/**
 * Template Database Row Class
 *
 * @author      Stian Didriksen <http://nooku.assembla.com/profile/stiandidriksen>
 * @category    Nooku
 * @package     Nooku_Server
 * @subpackage  Templates
 */
class ComTemplatesDatabaseRowTemplate extends KDatabaseRowAbstract
{
    protected function _initialize(KConfig $config)
    {
        $config->append(array(
            'identity_column'   => 'name'
        ));

        parent::_initialize($config);
    }

    /**
     * Set the row data
     *
     * Customized to set metadata from xml
     *
     * @param   mixed   Either and associative array, an object or a KDatabaseRow
     * @param   boolean If TRUE, update the modified information for each column being set. 
     *                  Default TRUE
     * @return  KDatabaseRowAbstract
     */
     public function setData( $data, $modified = true )
     {
        parent::setData($data, $modified);

        if(isset($this->application, $this->name)) 
        { 
             $this->_data = array_merge(array('pages' => '', 'ini' => ''), $this->_data);

            if($this->default) {
                $this->_data['pages'] = 'all';
            } elseif(!$this->assigned) {
                $this->_data['pages'] = 'none';
            }

            $this->_data['ini_file'] = $this->_data['path'].'/params.ini';
            
            if(file_exists($this->_data['ini_file'])) {
                $this->_data['ini'] = file_get_contents($this->_data['ini_file']);
            }
            
            $this->_data['xml_file'] = $this->_data['path'].'/templateDetails.xml';
            $this->_data['xml']      = simplexml_load_file($this->_data['xml_file']);

            $keys        = array('creationDate', 'author', 'copyright', 'authorEmail', 'authorUrl', 'version', 'description');
            $metadata    = array_intersect_key((array)$this->_data['xml'], array_fill_keys($keys, 1));
            $this->_data = array_merge($metadata, $this->_data);
        }

        return $this;
    }
    
    /**
     * Templates are newer new, they simply exist or don't
     *
     * @return boolean
     */
    public function isNew()
    {
        return false;
    }
}