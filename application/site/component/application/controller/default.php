<?php
/**
 * @package     Nooku_Components
 * @subpackage  Default
 * @copyright   Copyright (C) 2007 - 2012 Johan Janssens. All rights reserved.
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://www.nooku.org
 */

use Nooku\Library;

/**
 * Model Controller
 *
 * @author      Johan Janssens <johan@nooku.org>
 * @package     Nooku_Components
 * @subpackage  Default
 */
class ApplicationControllerDefault extends Library\ControllerModel
{
	/**
	 * The limit information
	 *
	 * @var	array
	 */
	protected $_limit;

	/**
	 * Constructor
	 *
	 * @param 	object 	An optional Library\ObjectConfig object with configuration options.
	 */
	public function __construct(Library\ObjectConfig $config)
	{
		parent::__construct($config);

		$this->_limit = $config->limit;
	}

	/**
     * Initializes the default configuration for the object
     *
     * Called from {@link __construct()} as a first step of object instantiation.
     *
     * @param 	object 	An optional Library\ObjectConfig object with configuration options.
     * @return void
     */
    protected function _initialize(Library\ObjectConfig $config)
    {
        $config->append(array(
            'limit' => array('max' => 100, 'default' => $this->getObject('application')->getCfg('list_limit'))
        ));

        parent::_initialize($config);
    }

    /**
     * Display action
     *
     * @param   Library\CommandContext A command context object
     * @return  Library\DatabaseRow(set)   A row(set) object containing the data to display
     */
    protected function _actionRender(Library\CommandContext $context)
    {
        JFactory::getLanguage()->load($this->getIdentifier()->package);
        return parent::_actionRender($context);
    }

	/**
     * Browse action
     *
     * Use the application default limit if no limit exists in the model and limit the
     * limit to a maximum.
     *
     * @param   Library\CommandContext A command context object
     * @return  Library\DatabaseRow(set)   A row(set) object containing the data to display
     */
    protected function _actionBrowse(Library\CommandContext $context)
    {
        if($this->isDispatched())
        {
            $limit = $this->getModel()->get('limit');

            //If limit is empty use default
            if(empty($limit)) {
                $limit = $this->_limit->default;
            }

            //Force the maximum limit
            if($limit > $this->_limit->max) {
                $limit = $this->_limit->max;
            }

            $this->getModel()->set('limit', $limit);
        }

        return parent::_actionBrowse($context);
    }
}