<?php
/**
 * Kodekit Platform - http://www.timble.net/kodekit
 *
 * @copyright	Copyright (C) 2007 - 2014 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license		MPL v2.0 <https://www.mozilla.org/en-US/MPL/2.0>
 * @link		https://github.com/timble/kodekit-platform for the canonical source repository
 */

namespace Kodekit\Library;

/**
 * Empty Model
 *
 * @author  Johan Janssens <http://github.com/johanjanssens>
 * @package Kodekit\Library\Model
 */
final class ModelEmpty extends ModelAbstract
{
    /**
     * Constructor
     *
     * @param  ObjectConfig $config    An optional ObjectConfig object with configuration options
     */
    public function __construct(ObjectConfig $config)
    {
        parent::__construct($config);

        $this->_entity = $this->getObject('lib:model.entity.immutable');
    }

    /**
     * Get the total number of entities
     *
     * @param ModelContext $context A model context object
     * @return string  The output of the view
     */
    protected function _actionCount(ModelContext $context)
    {
        return 0;
    }

    /**
     * Reset the model
     *
     * @param  string $name The state name being changed
     * @return void
     */
    protected function _actionReset(ModelContext $context)
    {

    }
}