<?php
/**
 * Nooku Platform - http://www.nooku.org/platform
 *
 * @copyright	Copyright (C) 2011 - 2014 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		https://github.com/nooku/nooku-platform for the canonical source repository
 */

namespace Nooku\Component\Languages;

use Nooku\Library;

/**
 * Languages Model Entity
 *
 * @author  Johan Janssens <http://github.com/johanjanssens>
 * @package Nooku\Component\Languages
 */
class ModelEntityLanguages extends Library\ModelEntityRowset
{
    /**
     * The active language
     *
     * @var ModelEntityLanguage
     */
    protected $_active;

    /**
     * The primary language
     *
     * @var ModelEntityLanguage
     */
    protected $_primary;

    public function setActive($active)
    {
        if(is_numeric($active)) {
            $this->_active = $this->find($active);
        } else {
            $this->_active = $active;
        }

        //Set the translator locale
        $this->getObject('translator')->setLanguage($this->_active->iso_code);

        return $this;
    }

    public function getActive()
    {
        if(!isset($this->_active))
        {
            //Ensure we have a proper language tag
            $locale   = locale_get_default();
            $language = strtolower(locale_get_primary_language($locale));
            $region   = strtoupper(locale_get_region($locale));

            $language = $this->find(array('iso_code' => $language.'-'.$region));

            if(!$language) {
                $language = $this->getPrimary();
            }

            $this->_active = $language;
        }

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