<?php
/**
 * Kodekit Component - http://www.timble.net/kodekit
 *
 * @copyright	Copyright (C) 2011 - 2016 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license		MPL v2.0 <https://www.mozilla.org/en-US/MPL/2.0>
 * @link		https://github.com/timble/kodekit-languages for the canonical source repository
 */

namespace Kodekit\Component\Languages;

use Kodekit\Library;

/**
 * Languages Composite Model
 *
 * @author  Johan Janssens <http://github.com/johanjanssens>
 * @package Kodekit\Component\Languages
 */
class ModelCompositeLanguages extends ModelLanguages implements Library\ObjectSingleton
{
    /**
     * The active language
     *
     * @var ModelEntityLanguage
     */
    protected $_active;

    /**
     * The default language
     *
     * @var ModelEntityLanguage
     */
    protected $_default;

    protected function _initialize(Library\ObjectConfig $config)
    {
        $config->append(array(
            'decorators'     => array('lib:model.composite'),
            'state_defaults' => array(
                'enabled'     => true,
                'application' => APPLICATION_NAME,
            )
        ));

        parent::_initialize($config);
    }

    public function setActive($active)
    {
        if(is_numeric($active)) {
            $this->_active = $this->fetch()->find($active);
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

            $language = $this->fetch()->find(array('iso_code' => $language.'-'.$region));

            if(!$language) {
                $language = $this->getDefault();
            }

            $this->_active = $language;
        }

        return $this->_active;
    }

    public function getDefault()
    {
        if(!isset($this->_default)) {
            $this->_default = $this->fetch()->find(array('default' => 1));
        }

        return $this->_default;
    }
}