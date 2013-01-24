<?php
/**
 * @version     $Id: page.php 3035 2011-10-09 16:57:12Z johanjanssens $
 * @package     Nooku_Server
 * @subpackage  Pages
 * @copyright   Copyright (C) 2011 Timble CVBA and Contributors. (http://www.timble.net).
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://www.nooku.org
 */

/**
 * Page Database Row Class
 *
 * @author      Gergo Erdosi <http://nooku.assembla.com/profile/gergoerdosi>
 * @package     Nooku_Server
 * @subpackage  Pages
 */

class ComPagesDatabaseRowPage extends KDatabaseRowTable
{
    public function setProperty($column, $value, $modified)
    {
        if($modified && $this->_data[$column] != $value) {
            $this->_modified[$column] = true;
        }

        $this->set($column, $value);

        return $this;
    }

    public function set($column, $value)
    {
        // If type has changed, set the corresponding behavior.
        if($column == 'type' && $this->type != $value && is_object($this->_table))
        {
            $identifier = clone $this->getIdentifier();
            $identifier->path = array('database', 'behavior');
            $identifier->name = 'typable';

            $table = $this->getTable();

            // Detach the old behavior.
            if(isset($this->type))
            {
                if($table->hasBehavior('typable'))
                {
                    $behavior = $table->getBehavior($identifier);
                    $table->detachBehavior($behavior);
                }

                $this->unmixin($identifier);
            }

            // Attach the new behavior.
            $table->attachBehavior($identifier, array('strategy' => $value, 'mixer' => $this));
            $this->mixin($table->getBehavior($identifier));
        }

        $this->_data[$column] = $value;

        return $this;
    }

    public function setData($data, $modified = true)
    {
        if($data instanceof KDatabaseRowInterface) {
            $data = $data->toArray();
        } else {
            $data = (array) $data;
        }

        foreach($data as $column => $value) {
            $this->setProperty($column, $value, $modified);
        }

        return $this;
    }

    public function unmixin($mixin)
    {
        switch($mixin)
        {
            case ($mixin instanceof KServiceIdentifier):
                $identifier = $mixin;
                break;

            case ($mixin instanceof KMixinInterface):
                $identifier = $mixin->getIdentifier();
                break;

            default:
                // Create the complete identifier if a partial identifier was passed.
                if(is_string($mixin) && strpos($mixin, '.') === false)
                {
                    $identifier = clone $this->getIdentifier();
                    $identifier->path = 'mixin';
                    $identifier->name = $mixin;
                }
                else $identifier = $this->getIdentifier($mixin);
        }

        $identifier = (string) $identifier;

        // Unset the mixed methods.
        foreach($this->_mixed_methods as $method => $object)
        {
            if((string) $object->getIdentifier() == $identifier) {
                unset($this->_mixed_methods[$method]);
            }
        }

        return $this;
    }
}
