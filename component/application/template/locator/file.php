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

/**
 * File Override Locator
 *
 * @author  Johan Janssens <http://github.com/johanjanssens>
 * @package Kodekit\Library\TemplateLoaderComponent
 */
class TemplateLocatorFile extends Library\TemplateLocatorFile
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
            //Qualify partial templates.
            if(dirname($info['url']) === '.')
            {
                if(empty($info['base'])) {
                    throw new \RuntimeException('Cannot qualify partial template path');
                }

                $path = dirname($info['base']);
            }
            else $path = dirname($info['url']);

            $file   = pathinfo($info['url'], PATHINFO_FILENAME);
            $format = pathinfo($info['url'], PATHINFO_EXTENSION);
            $path   = $this->_override_path.'/'.str_replace(parse_url($path, PHP_URL_SCHEME).'://', '', $path);

            // Remove /view from the end of the override path
            if (substr($path, strrpos($path, '/')) === '/view') {
                $path = substr($path, 0, -5);
            }

            if(!$result = $this->realPath($path.'/'.$file.'.'.$format))
            {
                $pattern = $path.'/'.$file.'.'.$format.'.*';
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
        }

        return parent::find($info);
    }
}