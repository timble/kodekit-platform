<?php
/**
 * Nooku Platform - http://www.nooku.org/platform
 *
 * @copyright   Copyright (C) 2007 - 2013 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        https://github.com/nooku/nooku-platform for the canonical source repository
 */

namespace Nooku\Library;

/**
 * Mime type finder chain
 *
 * You can add custom mimetype resolvers by calling the registerResolve() method. Custom resolvers are always called
 * before any default ones.
 *
 *     $mimetype = $this->getObject('filesystem.mimetype');
 *     $mimetype->registerResolver('custom.mimetype.identifier');
 *
 * If you want to change the order of the default resolvers, just re-register your preferred one as a custom one. The
 * last registered resolver is preferred over previously registered ones.
 *
 * Re-registering a built-in resolver also allows you to configure it:
 *
 *     $mimetype = $this->getObject('filesystem.mimetype');
 *     $mimetype->registerResolver($this->getObject('filesystem.mimetype.fileinfo', array(
 *         'magic_file' => '/path/to/magic/file'
 *     )));
 *
 * @author  Ercan Ozkaya <https://github.com/ercanozkaya>
 * @package Nooku\Library\Filesystem\Mimetype
 */
class FilesystemMimetype extends FilesystemMimetypeAbstract implements ObjectSingleton
{
    /**
     * List of registered mimetype resolvers
     *
     * @var array
     */
    private $__resolvers;

    /**
     * Registers all natively provided mime type resolvers.
     *
     * @param ObjectConfig $config An optional ObjectConfig object with configuration options
     */
    public function __construct(ObjectConfig $config)
    {
        parent::__construct($config);

        //Register the resolvers
        $resolvers = ObjectConfig::unbox($config->resolvers);

        foreach ($resolvers as $key => $value)
        {
            if (is_numeric($key)) {
                $this->registerResolver($value);
            } else {
                $this->registerResolver($key, $value);
            }
        }
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
        if (empty($config->resolvers))
        {
            $config->append(array(
                'resolvers' => array('extension')
            ));
        }

        parent::_initialize($config);
    }

    /**
     * Registers a new mime type resolver.
     *
     * The last added mimetype resolver is preferred over previously registered ones.
     *
     * @param   mixed $resolver An object that implements KFilesystemMimetypeInterface, KObjectIdentifier object
     *                          or valid identifier string
     * @param  array $config  An optional associative array of configuration options
     * @return $this
     */
    public function registerResolver($resolver, array $config = array())
    {
        if(!($resolver instanceof FilesystemMimetypeInterface))
        {
            if(is_string($resolver) && strpos($resolver, '.') === false )
            {
                $identifier = $this->getIdentifier()->toArray();
                $identifier['path'][] = 'mimetype';
                $identifier['name'] = $resolver;

                $identifier = $this->getIdentifier($identifier);
            }
            else $identifier = $this->getIdentifier($resolver);

            $resolver = $identifier;
        }
        else $identifier = $resolver->getIdentifier();

        //Merge the config for the resolver
        $identifier->getConfig()->merge($config);

        //Store the resolver
        $name = $identifier->name;

        if(isset($this->__resolvers[$name])) {
            unset($this->__resolvers[$name]);
        }

        $this->__resolvers[$name] = $resolver;

        return $this;
    }



    /**
     * Tries to find the mime type of the given file from it's file path.
     *
     * The file is passed to each registered mime type resolver in FILO order. Once a resolver returns a value that
     * is not NULL, the result is returned.
     *
     * @param string $path The path to the file
     * @throws \LogicException   If the file cannot be found
     * @throws \RuntimeException If the file is not readable
     * @throws \UnexpectedValueException If the resolver doesn't implement KFilesystemMimetypeInterface
     * @return string The mime type or NULL, if none could be found
     */
    public function fromPath($path)
    {
        if (!is_file($path)) {
            throw new \RuntimeException('File not found at '.$path);
        }

        if (!is_readable($path)) {
            throw new \RuntimeException('File not readable at '.$path);
        }

        $mimetype = null;

        foreach (array_reverse($this->__resolvers) as $name => $resolver)
        {
            //Lazy create the resolver
            if(!($resolver instanceof FilesystemMimetypeInterface))
            {
                $resolver = $this->getObject($resolver);

                if (!$resolver instanceof FilesystemMimetypeInterface) {
                    throw new \UnexpectedValueException('Resolver does not implement KFilesystemMimetypeInterface');
                }

                $this->__resolvers[$name] = $resolver;
            }

            /* @var $resolver FilesystemMimetypeInterface */
            if (null !== $mimetype = $resolver->fromPath($path)) {
                break;
            }
        }

        return $mimetype;
    }

    /**
     * Find the mime type of the given stream
     *
     * @param FilesystemStreamInterface $stream
     * @throws \RuntimeException If the stream is not readable
     * @return string The mime type or NULL, if none could be guessed
     */
    public function fromStream(FilesystemStreamInterface $stream)
    {
        if (!$stream->isReadable()) {
            throw new \RuntimeException('Stream not readable');
        }

        $mimetype = null;

        foreach (array_reverse($this->__resolvers) as $name => $resolver)
        {
            //Lazy create the resolver
            if(!($resolver instanceof FilesystemMimetypeInterface))
            {
                $resolver = $this->getObject($resolver);

                if (!$resolver instanceof FilesystemMimetypeInterface) {
                    throw new \UnexpectedValueException('Resolver does not implement KFilesystemMimetypeInterface');
                }

                $this->__resolvers[$name] = $resolver;
            }

            /* @var $resolver FilesystemMimetypeInterface */
            if (null !== $mimetype = $resolver->fromStream($stream)) {
                break;
            }
        }

        return $mimetype;
    }

    /**
     * Check if a resolver has already been registered
     *
     * @param 	string	$resolver The name of the resolver
     * @return  boolean	TRUE if the resolver exists, FALSE otherwise
     */
    public function isRegistered($resolver)
    {
        return isset($this->__resolvers[$resolver]);
    }
}