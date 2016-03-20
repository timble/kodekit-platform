<?php
/**
 * Kodekit Component - http://www.timble.net/kodekit
 *
 * @copyright	Copyright (C) 2007 - 2013 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license		MPL v2.0 <https://www.mozilla.org/en-US/MPL/2.0>
 * @link		https://github.com/timble/kodekit-application for the canonical source repository
 */

namespace Kodekit\Component\Application;

use Kodekit\Library;
use Kodekit\Component\Pages;

/**
 * Component Override Locator
 *
 * @author  Johan Janssens <http://github.com/johanjanssens>
 * @package Kodekit\Library\TemplateLoaderComponent
 */
class TemplateLocatorModule extends Pages\TemplateLocatorModule
{
    /**
     * The override path
     *
     * @var string
     */
    protected $_override_path;

    /**
     * Constructor.
     *
     * @param Library\ObjectConfig $config  An optional ObjectConfig object with configuration options
     */
    public function __construct(Library\ObjectConfig $config)
    {
        parent::__construct($config);

        $this->_override_path = $config->override_path;
    }

    /**
     * Initializes the options for the object
     *
     * Called from {@link __construct()} as a first step of object instantiation.
     *
     * @param  Library\ObjectConfig $config An optional ObjectConfig object with configuration options.
     * @return  void
     */
    protected function _initialize(Library\ObjectConfig $config)
    {
        $config->append(array(
            'override_path' => ''
        ));

        parent::_initialize($config);
    }

    /**
     * Find a template override
     *
     * @param array  $info      The path information
     * @return bool|mixed
     */
    public function find(array $info)
    {
        if(!empty($this->_override_path))
        {
            //If no type exists create a glob pattern
            if(!empty($info['type'])){
                $filepath = $info['package'].'/'.implode('/', $info['path']).'/'.$info['file'].'.'.$info['format'].'.'.$info['type'];
            } else {
                $filepath = $info['package'].'/'.implode('/', $info['path']).'/'.$info['file'].'.'.$info['format'].'.*';
            }

            $pattern = $this->_override_path.'/'.$filepath;
            $results = glob($pattern);

            //Try to find the file
            if ($results)
            {
                foreach($results as $file)
                {
                    if($result = $this->realPath($file)) {
                        return $result;
                    }
                }
            }
        }

        return parent::find($info);
    }
}