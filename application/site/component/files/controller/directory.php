<?php
/**
 * @version     $Id$
 * @package     Nooku_Components
 * @subpackage  Files
 * @copyright   Copyright (C) 2011 - 2012 Timble CVBA and Contributors. (http://www.timble.net).
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://www.nooku.org
 */

use Nooku\Library;

/**
 * Folder Controller Class
 *
 * @author      Ercan Ozkaya <http://nooku.assembla.com/profile/ercanozkaya>
 * @package     Nooku_Components
 * @subpackage  Files
 */
class FilesControllerDirectory extends Library\ControllerView
{
    protected $_model;

    public function __construct(Library\ObjectConfig $config)
    {
        parent::__construct($config);
        $this->_model = $config->model;
    }

    protected function _initialize(Library\ObjectConfig $config)
    {
        $config->append(array('model' => $this->getIdentifier()->name));
        parent::_initialize($config);
    }

    public function getView() {
        $view = parent::getView();

        $view->setModel($this->getModel());

        return $view;
    }

    /**
     * Get the model object attached to the controller
     *
     * @throws	\UnexpectedValueException	If the model doesn't implement the ModelInterface
     * @return	ModelAbstract
     */
    public function getModel()
    {
        if(!$this->_model instanceof Library\ModelInterface)
        {
            if(!($this->_model instanceof Library\ObjectIdentifier)) {
                $this->setModel($this->_model);
            }

            $this->_model = $this->getObject($this->_model);

            //Inject the request into the model state
            $this->_model->setState($this->getRequest()->query->toArray());

            if(!$this->_model instanceof Library\ModelInterface)
            {
                throw new \UnexpectedValueException(
                    'Model: '.get_class($this->_model).' does not implement ModelInterface'
                );
            }
        }

        return $this->_model;
    }

    /**
     * Method to set a model object attached to the controller
     *
     * @param	mixed	$model An object that implements ObjectInterface, ObjectIdentifier object
     * 					       or valid identifier string
     * @return	ControllerView
     */
    public function setModel($model)
    {
        if(!($model instanceof Library\ModelInterface))
        {
            if(is_string($model) && strpos($model, '.') === false )
            {
                // Model names are always plural
                if(Library\StringInflector::isSingular($model)) {
                    $model = Library\StringInflector::pluralize($model);
                }

                $identifier			= clone $this->getIdentifier();
                $identifier->path	= array('model');
                $identifier->name	= $model;
            }
            else $identifier = $this->getIdentifier($model);

            $model = $identifier;
        }

        $this->_model = $model;

        return $this;
    }
}