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
	 * @param Library\ObjectConfig 	$config An optional Library\ObjectConfig object with configuration options.
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
     * @param Library\ObjectConfig $config 	An optional Library\ObjectConfig object with configuration options.
     * @return void
     */
    protected function _initialize(Library\ObjectConfig $config)
    {
        $config->append(array(
            'toolbars' => array($this->getIdentifier()->name),
            'limit'    => array('max' => 100, 'default' => $this->getObject('application')->getCfg('list_limit'))
        ));

        parent::_initialize($config);
    }

 	/**
     * Read action
     *
     * This functions implements an extra check to hide the main menu is the view name
     * is singular (item views)
     *
     * @param  Library\CommandContext $context A command context object
     * @return Library\DatabaseRowInterface    A row object containing the selected row
     */
    protected function _actionRead(Library\CommandContext $context)
    {
        //Perform the read action
        $row = parent::_actionRead($context);

        //Add the notice if the row is locked
        if(isset($row))
        {
            if($row->isLockable() && $row->locked()) {
                $context->user->addFlashMessage($row->lockMessage(), 'notice');
            }
        }

        return $row;
    }

	/**
     * Browse action
     *
     * Use the application default limit if no limit exists in the model and limit the limit to a maximum.
     *
     * @param   Library\CommandContext $context A command context object
     * @return  Library\DatabaseRow(set)Interface   A row(set) object containing the data to display
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