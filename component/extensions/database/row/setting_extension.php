<?php
/**
 * Nooku Framework - http://www.nooku.org
 *
 * @copyright	Copyright (C) 2011 - 2013 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		git://git.assembla.com/nooku-framework.git for the canonical source repository
 */

namespace Nooku\Component\Extensions;

use Nooku\Library;

/**
 * Extension Setting Database Row
 *
 * @author  Johan Janssens <http://nooku.assembla.com/profile/johanjanssens>
 * @package Nooku\Component\Extensions
 */
class DatabaseRowSetting_Extension extends DatabaseRowSetting
{
    /**
     * The component
     *
     * @var string
     */
    protected $_id;

    /**
     * The component table
     *
     * @var string
     */
    protected $_table;

    /**
     * Constructor
     *
     * @param   object  An optional Library\ObjectConfig object with configuration options.
     */
    public function __construct(Library\ObjectConfig $config)
    {
        parent::__construct($config);

        $this->_id    = $config->id;
        $this->_table = $config->table;
    }

    /**
     * Initializes the options for the object
     *
     * Called from {@link __construct()} as a first step of object instantiation.
     *
     * @param   object  An optional Library\ObjectConfig object with configuration options.
     * @return void
     */
    protected function _initialize(Library\ObjectConfig $config)
    {
        $config->append(array(
            'id'     => '',
            'table'  => 'com:extensions.database.table.extensions'
        ));

        parent::_initialize($config);
    }

    /**
     * Saves the settings to the database.
     *
     * @return boolean	If successfull return TRUE, otherwise FALSE
     */
    public function save()
    {
        if(!empty($this->_modified))
        {
            $row = $this->getObject($this->_table)->select($this->_id, Library\Database::FETCH_ROW);
            $row->params = $this->_data;

            return (bool) $row->save();
        }

        return true;
    }

    /**
     * The setting type
     *
     * @return string 	The setting type
     */
    public function getType()
    {
        return 'extension';
    }
}