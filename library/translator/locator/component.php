<?php
/**
 * Kodekit Platform - http://www.timble.net/kodekit
 *
 * @copyright   Copyright (C) 2007 - 2014 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license     MPL v2.0 <https://www.mozilla.org/en-US/MPL/2.0>
 * @link        https://github.com/timble/kodekit-platform for the canonical source repository
 */

namespace Kodekit\Library;

/**
 * Component Translator Locator
 *
 * @author  Johan Janssens <http://github.com/johanjanssens>
 * @package Kodekit\Library\Translator\Locator\Component
 */
class TranslatorLocatorComponent extends TranslatorLocatorIdentifier
{
    /**
     * The locator name
     *
     * @var string
     */
    protected static $_name = 'com';

    /**
     * Find a template path
     *
     * @param array  $info      The path information
     * @return array
     */
    public function find(array $info)
    {
        //Base paths
        $paths = $this->getObject('object.bootstrapper')->getComponentPath($info['package'], $info['domain']);

        $result = array();
        foreach($paths as $basepath)
        {
            $info['path'] = $basepath.'/resources/language';

            if($path = parent::find($info)) {
                $result = array_merge($result, $path);
            }
        }

        return $result;
    }
}
