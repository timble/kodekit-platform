<?php
/**
 * @version     $Id: listbox.php 3031 2011-10-09 14:21:07Z johanjanssens $
 * @package     Nooku_Server
 * @subpackage  Pages
 * @copyright   Copyright (C) 2011 Timble CVBA and Contributors. (http://www.timble.net).
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://www.nooku.org
 */

/**
 * Module Template Helper Class
 *
 * @author      Johan Janssens <http://nooku.assembla.com/profile/johanjanssens>
 * @package     Nooku_Server
 * @subpackage  Pages
 */

class ComPagesTemplateHelperModule extends KTemplateHelperAbstract
{
    /**
     * Modules
     *
     * @var KDatabaseRowsetInterface
     */
    protected $_modules;

    /**
     * Constructor.
     *
     * @param   object  An optional KConfig object with configuration options
     */
    public function __construct(KConfig $config)
    {
        parent::__construct($config);

        if (is_null($config->modules))
        {
            throw new InvalidArgumentException(
                'modules [KDatabaseRowsetInterface] config option is required'
            );
        }

        $this->setModules($config->modules);
    }

    /**
     * Initializes the options for the object
     *
     * Called from {@link __construct()} as a first step of object instantiation.
     *
     * @param   object  An optional KConfig object with configuration options
     * @return void
     */
    protected function _initialize(KConfig $config)
    {
        $config->append(array(
            'modules' => null,
        ));

        parent::_initialize($config);
    }

    /**
     * Set the modules
     *
     * @param KDatabaseRowsetInterface $modules
     */
    public function setModules(KDatabaseRowsetInterface $modules)
    {
        $this->_modules = $modules;
    }

    /**
     * Get the modules
     *
     * @return KDatabaseRowsetInterfaces
     */
    public function getModules()
    {
        return $this->_modules;
    }

    /**
     * Count the modules based on a condition of positions
     *
     * @param array $config
     * @return integer Returns the result of the evaluated condition
     */
    public function count($config = array())
    {
        $config = new KConfig($config);
        $config->append(array(
            'condition' => ''
        ));

        $result = 0;
        if(!empty($config->condition))
        {
            $operators = '(\+|\-|\*|\/|==|\!=|\<\>|\<|\>|\<=|\>=|and|or|xor)';
            $words = preg_split('# ' . $operators . ' #', $config->condition, null, PREG_SPLIT_DELIM_CAPTURE);
            for ($i = 0, $n = count($words); $i < $n; $i += 2)
            {
                // Odd parts (modules)
                $position = strtolower($words[$i]);
                $words[$i] = count($this->getModules()->find(array('position' => $position)));
            }

            $str = 'return ' . implode(' ', $words) . ';';
            $result = eval($str);
        }

        return $result;
    }
}