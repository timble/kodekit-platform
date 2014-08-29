<?php
/**
 * Nooku Platform - http://www.nooku.org/platform
 *
 * @copyright	Copyright (C) 2007 - 2014 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		http://github.com/nooku/nooku-platform for the canonical source repository
 */

namespace Nooku\Library;

/**
 * Nooku Template Engine
 *
 * @author  Johan Janssens <http://github.com/johanjanssens>
 * @package Nooku\Library\Template\Engine\Abstract
 */
class TemplateEngineNooku extends TemplateEngineAbstract
{
    /**
     * The engine file types
     *
     * @var string
     */
    protected static $_file_types = array('php');

    /**
     * Template stack
     *
     * Used to track recursive load calls during template evaluation
     *
     * @var array
     */
    protected $_stack;

    /**
     * The template buffer
     *
     * @var FilesystemStreamBuffer
     */
    protected $_buffer;

    /**
     * Constructor
     *
     * @param ObjectConfig $config   An optional ObjectConfig object with configuration options
     */
    public function __construct(ObjectConfig $config)
    {
        parent::__construct($config);

        //Reset the stack
        $this->_stack = array();

        //Intercept template exception
        $this->getObject('exception.handler')->addHandler(array($this, 'handleException'), true);
    }

    /**
     * Initializes the options for the object
     *
     * Called from {@link __construct()} as a first step of object instantiation.
     *
     * @param  ObjectConfig $config An optional ObjectConfig object with configuration options
     */
    protected function _initialize(ObjectConfig $config)
    {
        $config->append(array(
            'functions' => array(
                'import' => array($this, '_import'),
            ),
        ));

        parent::_initialize($config);
    }

    /**
     * Load a template by path
     *
     * @param   string  $url      The template url
     * @throws \InvalidArgumentException If the template could not be located
     * @throws \RuntimeException         If the template could not be loaded
     * @throws \RuntimeException         If the template could not be compiled
     * @return TemplateEngineNooku
     */
    public function load($url)
    {
        //Locate the template
        $file = $this->_locate($url);

        //Push the template on the stack
        array_push($this->_stack, array('url' => $url, 'file' => $file));

        if(!$this->_content = $this->isCached($file))
        {
            //Load the template
            if(!$content = file_get_contents($file)) {
                throw new \RuntimeException(sprintf('The template "%s" cannot be loaded.', $file));
            }

            //Compile the template
            if(!$content = $this->_compile($content)) {
                throw new \RuntimeException(sprintf('The template "%s" cannot be compiled.', $file));
            }

            $this->_content = $this->_buffer($file, $content);
        }

        return $this;
    }

    /**
     * Render a template
     *
     * @param   array   $data   The data to pass to the template
     * @throws \RuntimeException If the template could not be evaluated
     * @return TemplateEngineNooku
     */
    public function render(array $data = array())
    {
        parent::render($data);

        $content = '';
        if(!empty($this->_content))
        {
            //Evaluate the template
            if (!$content = $this->_evaluate($this->_content, $this->getData())) {
                throw new \RuntimeException(sprintf('The template "%s" cannot be evaluated.', $content));
            }

            //Remove the template from the stack
            array_pop($this->_stack);
        }

        return $content;
    }

    /**
     * Get the template content
     *
     * @return  string
     */
    public function getContent()
    {
        return file_get_contents($this->_content);
    }

    /**
     * Set the template content from a string
     *
     * @param  string  $content  The template content
     * @throws \RuntimeException If the template could not be compiled
     * @return TemplateEngineNooku
     */
    public function setContent($content)
    {
        $name = crc32($content);

        if(!$file = $this->isCached($name))
        {
            //Compile the template
            if(!$content = $this->_compile($content)) {
                throw new \RuntimeException(sprintf('The template content cannot be compiled.'));
            }

            $file = $this->_buffer($name, $content);
        }

        $this->_content = $file;

        //Push the template on the stack
        array_push($this->_stack, array('url' => '', 'file' => $file));
        return $this;
    }

    /**
     * Handle template exceptions
     *
     * If an ErrorException is thrown create a new exception and set the file location to the real template file.
     *
     * @param  \Exception  $exception
     * @return void
     */
    public function handleException(\Exception &$exception)
    {
        if($template = end($this->_stack))
        {
            if($this->_content == $exception->getFile())
            {
                //Prevents any partial templates from leaking.
                ob_get_clean();

                //Re-create the exception and set the real file path
                if($exception instanceof \ErrorException)
                {
                    $class = get_class($exception);

                    $exception = new $class(
                        $exception->getMessage(),
                        $exception->getCode(),
                        $exception->getSeverity(),
                        $template['file'],
                        $exception->getLine(),
                        $exception
                    );
                }
            }
        }
    }

    /**
     * Locate the template
     *
     * @param   string  $url The template url
     * @return string   The template real path
     */
    protected function _locate($url)
    {
        //Create the locator
        if($template = end($this->_stack)) {
            $base = $template['url'];
        } else {
            $base = null;
        }

        if(!$location = parse_url($url, PHP_URL_SCHEME)) {
            $location = $base;
        } else {
            $location = $url;
        }

        $locator = $this->getObject('template.locator.factory')->createLocator($location);

        //Locate the template
        if (!$file = $locator->setBasePath($base)->locate($url)) {
            throw new \InvalidArgumentException(sprintf('The template "%s" cannot be located.', $url));
        }

        return $file;
    }

    /**
     * Import a partial template
     *
     * If importing a partial merges the data passed in with the data from the call to render. If importing a different
     * template type jump out of engine scope to back to the template.
     *
     * @param   string  $url      The template url
     * @param   array   $data     The data to pass to the template
     * @return  string The rendered template content
     */
    protected function _import($url, array $data = array())
    {
        $result = '';

        //Locate the template
        if($file = $this->_locate($url))
        {
            $type = pathinfo($file, PATHINFO_EXTENSION);

            if(in_array($type, $this->getFileTypes()))
            {
                $data = array_merge((array) $this->getData(), $data);
                $result = $this->load($url)->render($data);
            }
            else  $result = $this->getTemplate()->load($file)->render($data);
        }

        return $result;
    }

    /**
     * Compile the template
     *
     * @param   string  $content      The template content to compile
     * @return string The compiled template content
     */
    protected function _compile($content)
    {
        //Convert PHP tags
        if (!ini_get('short_open_tag'))
        {
            // convert "<?=" to "<?php echo"
            //$find = '/\<\?\s*=\s*(.*?)/';
            //$replace = "<?php echo \$1";
            //$content = preg_replace($find, $replace, $content);

            // convert "<?" to "<?php"
            $find = '/\<\?(?:php)?\s*(.*?)/';
            $replace = "<?php \$1";
            $content = preg_replace($find, $replace, $content);
        }

        //Compile to valid PHP
        $tokens = token_get_all($content);

        $result = '';
        for ($i = 0; $i < sizeof($tokens); $i++)
        {
            if(is_array($tokens[$i]))
            {
                list($token, $content) = $tokens[$i];

                switch ($token)
                {
                    //Proxy registered functions through __call()
                    case T_STRING :

                        if(isset($this->_functions[$content]) )
                        {
                            $prev = (array) $tokens[$i-1];
                            $next = (array) $tokens[$i+1];

                            if($next[0] == '(' && $prev[0] !== T_OBJECT_OPERATOR) {
                                $result .= '$this->'.$content;
                                break;
                            }
                        }

                    default:
                        $result .= $content;
                        break;
                }
            }
            else $result .= $tokens[$i] ;
        }

        return $result;
    }

    /**
     * Evaluate the template using a simple sandbox
     *
     * @return string The evaluated template content
     */
    protected function _evaluate()
    {
        ob_start();

        extract($this->getData(), EXTR_SKIP);
        include $this->_content;
        $content = ob_get_clean();

        return $content;
    }

    /**
     * Buffer the template
     *
     * Write the template content to a file buffer. If cache is enabled the file will be buffer using cache settings
     * If caching is not enabled the file will be written to the temp path using a buffer://temp stream.
     *
     * @param  string $file     The file name
     * @param  string $content  The template content to cache
     * @throws \RuntimeException If the template cache path is not writable
     * @throws \RuntimeException If template cannot be cached
     * @return string    The buffer template file path
     */
    protected function _buffer($file, $content)
    {
        if(!$this->_cache)
        {
            if(!isset($this->_buffer)) {
                $this->_buffer = $this->getObject('filesystem.stream.factory')->createStream('buffer://temp', 'w+b');
            }

            $this->_buffer->truncate(0);
            $this->_buffer->write($content);

            $file = $this->_buffer->getPath();
        }
        else $file = $this->cache($file, $content);

        return $file;
    }
}
