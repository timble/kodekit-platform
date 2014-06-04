<?php
/**
 * Nooku Framework - http://www.nooku.org
 *
 * @copyright	Copyright (C) 2007 - 2013 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		git://git.assembla.com/nooku-framework.git for the canonical source repository
 */

namespace Nooku\Library;

/**
 * Component Template Locator
 *
 * @author  Johan Janssens <http://nooku.assembla.com/profile/johanjanssens>
 * @package Nooku\Library\TemplateLoaderComponent
 */
abstract class TemplateLocatorAbstract extends Object implements TemplateLocatorInterface
{
    /**
     * The type
     *
     * @var string
     */
    protected $_type = '';

    /**
     * Template object
     *
     * @var	TemplateInterface
     */
    protected $_template;

    /**
     * Constructor
     *
     * @param ObjectConfig $config	An optional ObjectConfig object with configuration options
     */
    public function __construct(ObjectConfig $config)
    {
        parent::__construct($config);

        // Set the template object
        $this->setTemplate($config->template);
    }

    /**
     * Initializes the options for the object
     *
     * Called from {@link __construct()} as a first step of object instantiation.
     *
     * @param  ObjectConfig $config An optional ObjectConfig object with configuration options
     * @return void
     */
    protected function _initialize(ObjectConfig $config)
    {
        $config->append(array(
            'template' => null,
        ));

        parent::_initialize($config);
    }

    /**
     * Get the type
     *
     * @return string
     */
    public function getType()
    {
        return $this->_type;
    }

    /**
     * Set the template object
     *
     * @return  TemplateInterface $template	The template object
     */
    public function setTemplate(TemplateInterface $template)
    {
        $this->_template = $template;
        return $this;
    }

    /**
     * Get the template object
     *
     * @return  object	The template object
     */
    public function getTemplate()
    {
        return $this->_template;
    }

    /**
     * Get a path from an file
     *
     * Function will check if the path is an alias and return the real file path
     *
     * @param  string $file The file path
     * @return string The real file path
     */
    final public function realPath($file)
    {
        $result = false;
        $path   = dirname($file);

        // Is the path based on a stream?
        if (strpos($path, '://') === false)
        {
            // Not a stream, so do a realpath() to avoid directory traversal attempts on the local file system.
            $path = realpath($path); // needed for substr() later
            $file = realpath($file);
        }

        // The substr() check added to make sure that the realpath() results in a directory registered so that
        // non-registered directories are not accessible via directory traversal attempts.
        if (file_exists($file) && substr($file, 0, strlen($path)) == $path) {
            $result = $file;
        }

        return $result;
    }
}