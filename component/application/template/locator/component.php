<?php
/**
 * Nooku Platform - http://www.nooku.org/platform
 *
 * @copyright	Copyright (C) 2007 - 2013 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		https://github.com/nooku/nooku-platform for the canonical source repository
 */

namespace Nooku\Component\Application;

use Nooku\Library;

/**
 * Component Theme Override Locator
 *
 * @author  Johan Janssens <http://nooku.assembla.com/profile/johanjanssens>
 * @package Nooku\Library\TemplateLoaderComponent
 */
class TemplateLocatorComponent extends Library\TemplateLocatorComponent
{
    /**
     * The theme path
     *
     * @var string
     */
    protected $_theme_path;

    /**
     * Constructor.
     *
     * @param Library\bjectConfig $config  An optional KObjectConfig object with configuration options
     */
    public function __construct(Library\ObjectConfig $config)
    {
        parent::__construct($config);

        $this->_theme_path = $config->theme_path;
    }

    /**
     * Initializes the options for the object
     *
     * Called from {@link __construct()} as a first step of object instantiation.
     *
     * @param  Library\ObjectConfig $config An optional KObjectConfig object with configuration options.
     * @return  void
     */
    protected function _initialize(Library\ObjectConfig $config)
    {
        $config->append(array(
            'theme_path' => ''
        ));

        parent::_initialize($config);
    }

    /**
     * Find a template path
     *
     * @param array  $info      The path information
     * @return bool|mixed
     */
    public function find(array $info)
    {
        if(!empty($this->_theme_path))
        {
            //If no type exists create a glob pattern
            if(!empty($info['type'])){
                $filepath = $info['package'].'/'.implode('/', $info['path']).'/'.$info['file'].'.'.$info['format'].'.'.$info['type'];
            } else {
                $filepath = $info['package'].'/'.implode('/', $info['path']).'/'.$info['file'].'.'.$info['format'].'.*';
            }

            $pattern = $this->_theme_path.'/templates/'.$filepath;

            //Try to find the file
            foreach(glob($pattern) as $file)
            {
                if($result = $this->realPath($file)) {
                    return $result;
                }
            }
        }

        return parent::find($info);
    }
}