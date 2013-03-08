<?php
/**
 * @package     Nooku_Components
 * @subpackage  Default
 * @copyright   Copyright (C) 2007 - 2012 Johan Janssens. All rights reserved.
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://www.nooku.org
 */

use Nooku\Framework;

/**
 * Model Controller
 *
 * @author      Johan Janssens <johan@nooku.org>
 * @package     Nooku_Components
 * @subpackage  Default
 */
class ComDefaultControllerModel extends Framework\ControllerModel
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
	 * @param 	object 	An optional Framework\Config object with configuration options.
	 */
	public function __construct(Framework\Config $config)
	{
		parent::__construct($config);

		$this->_limit = $config->limit;
	}

	/**
     * Initializes the default configuration for the object
     *
     * Called from {@link __construct()} as a first step of object instantiation.
     *
     * @param 	object 	An optional Framework\Config object with configuration options.
     * @return void
     */
    protected function _initialize(Framework\Config $config)
    {
        $config->append(array(
            'limit' => array('max' => 100, 'default' => $this->getService('application')->getCfg('list_limit'))
        ));

        parent::_initialize($config);
    }

    /**
     * Display action
     *
     * @param   Framework\CommandContext A command context object
     * @return  Framework\DatabaseRow(set)   A row(set) object containing the data to display
     */
    protected function _actionRender(Framework\CommandContext $context)
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
     * @param   Framework\CommandContext A command context object
     * @return  Framework\DatabaseRow(set)   A row(set) object containing the data to display
     */
    protected function _actionBrowse(Framework\CommandContext $context)
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