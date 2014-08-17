<?php
/**
 * Nooku Platform - http://www.nooku.org/platform
 *
 * @copyright	Copyright (C) 2011 - 2014 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		https://github.com/nooku/nooku-platform for the canonical source repository
 */

use Nooku\Library;

/**
 * Languages Database Rowset
 *
 * @author  Johan Janssens <http://github.com/johanjanssens>
 * @package Component\Application
 */
class ApplicationModelEntityLanguages extends Library\ModelEntityComposite implements Library\ObjectMultiton
{
    protected $_active;
    protected $_primary;

    public function __construct(Library\ObjectConfig $config )
    {
        parent::__construct($config);

        //TODO : Inject raw data using $config->data
        $languages = $this->getObject('com:languages.model.languages')
            ->enabled(true)
            ->application('site')
            ->fetch();

        $this->merge($languages);
    }

    public function setActive($active)
    {
        if(is_numeric($active)) {
            $this->_active = $this->find($active);
        } else {
            $this->_active = $active;
        }

        //Set the translator locale
        $this->getObject('translator')->setLocale($this->_active->iso_code);

        return $this;
    }

    public function getActive()
    {
        return $this->_active;
    }

    public function getPrimary()
    {
        if(!isset($this->_primary)) {
            $this->_primary = $this->find(array('primary' => 1));
        }

        return $this->_primary;
    }
}