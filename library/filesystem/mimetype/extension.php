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
 * Find the mime type of a file using the file extension. Lookups are performed using a provided JSON lookup file.
 *
 * JSON should be structured as a map of extension to mimetype
 *
 * @author  Ercan Ozkaya <https://github.com/ercanozkaya>
 * @package Nooku\Library\Filesystem\Mimetype
 */
class FilesystemMimetypeExtension extends FilesystemMimetypeAbstract
{
    /**
     * The mimetypes
     *
     * @var array
     */
    protected $_mimetypes;

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
            'file' => __DIR__.'/mimetypes.json'
        ));

        parent::_initialize($config);
    }

    /**
     * Find the mime type of the given stream
     *
     * @param FilesystemStreamInterface $stream
     * @return string The mime type or NULL, if none could be guessed
     */
    public function fromStream(FilesystemStreamInterface $stream)
    {
        $mimetype = null;

        if (static::isSupported())
        {
            if ($path = $stream->getPath()) {
                $mimetype = $this->_getMimetype(strtolower(pathinfo($path, PATHINFO_EXTENSION)));
            }
        }

        return $mimetype;
    }

    /**
     * Find the mime type of the file with the given path.
     *
     * @param string $path The path to the file
     * @return string The mime type or NULL, if none could be guessed
     */
    public function fromPath($path)
    {
        $mimetype = null;

        if (static::isSupported())
        {
            if (!is_file($path)) {
                throw new \RuntimeException('File not found at '.$path);
            }

            if (!is_readable($path)) {
                throw new \RuntimeException('File not readable at '.$path);
            }

            $mimetype = $this->_getMimetype(strtolower(pathinfo($path, PATHINFO_EXTENSION)));
        }

        return $mimetype;
    }

    /**
     * Return a mimetype for the given extension
     *
     * @param  string $extension
     * @return string|null
     */
    protected function _getMimetype($extension)
    {
        $mimetypes = $this->_getMimetypes();

        return isset($mimetypes[$extension]) ? $mimetypes[$extension] : null;
    }

    /**
     * Returns mimetypes list
     *
     * @return array
     */
    protected function _getMimetypes()
    {
        if(!isset($this->_mimetypes))
        {
            $file = $this->getConfig()->file;

            if (is_readable($file)) {
                $this->_mimetypes = json_decode(file_get_contents($file), true);
            }
        }

        return $this->_mimetypes;
    }
}