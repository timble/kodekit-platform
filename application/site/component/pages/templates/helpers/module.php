<?php
/**
 * @package     Nooku_Server
 * @subpackage  Pages
 * @copyright   Copyright (C) 2011 Timble CVBA and Contributors. (http://www.timble.net).
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://www.nooku.org
 */

use Nooku\Framework;

/**
 * Module Template Helper Class
 *
 * @author      Johan Janssens <http://nooku.assembla.com/profile/johanjanssens>
 * @package     Nooku_Server
 * @subpackage  Pages
 */

class ComPagesTemplateHelperModule extends Framework\TemplateHelperAbstract
{
    /**
     * Database rowset or identifier
     *
     * @var	string|object
     */
    protected $_modules;

    /**
     * Constructor.
     *
     * @param   object  An optional Framework\Config object with configuration options
     */
    public function __construct(Framework\Config $config)
    {
        parent::__construct($config);

        $this->_modules = $config->modules;
    }

    /**
     * Initializes the options for the object
     *
     * Called from {@link __construct()} as a first step of object instantiation.
     *
     * @param   object  An optional Framework\Config object with configuration options
     * @return void
     */
    protected function _initialize(Framework\Config $config)
    {
        $config->append(array(
            'modules' => null,
        ));

        parent::_initialize($config);
    }

    /**
     * Get the modules
     *
     * @throws	\UnexpectedValueException	If the request doesn't implement the Framework\DatabaseRowsetInterface
     * @return Framework\DatabaseRowsetInterface
     */
    public function getModules()
    {
        if(!$this->_modules instanceof Framework\DatabaseRowsetInterface)
        {
            $this->_modules = $this->getService($this->_modules);

            if(!$this->_modules instanceof Framework\DatabaseRowsetInterface)
            {
                throw new \UnexpectedValueException(
                    'Modules: '.get_class($this->_modules).' does not implement Framework\DatabaseRowsetInterface'
                );
            }
        }

        return $this->_modules;
    }

    /**
     * Count the modules based on a condition of positions
     *
     * @param  array|string $config
     * @return integer Returns the result of the evaluated condition
     */
    public function count($config = array())
    {
        //Condition is passed as a string
        if(is_string($config)) {
            $config = array('condition' => $config);
        }

        $result = 0;
        if(isset($config['condition']) && !empty($config['condition']))
        {
            $operators = '(\+|\-|\*|\/|==|\!=|\<\>|\<|\>|\<=|\>=|and|or|xor)';
            $words = preg_split('# ' . $operators . ' #', $config['condition'], null, PREG_SPLIT_DELIM_CAPTURE);
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