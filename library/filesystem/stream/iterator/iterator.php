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
 * FileSystem Stream Iterator
 *
 * @author  Johan Janssens <http://nooku.assembla.com/profile/johanjanssens>
 * @package Nooku\Library\FileSystem
 */
class FilesystemStreamIterator implements \SeekableIterator
{
    /**
     * Size of each chunk
     *
     * @var int
     */
    private $__stream;

    /**
     * The current chunk
     *
     * @var string
     */
    protected $_chunk;

    /**
     * Size of each chunk
     *
     * @var int
     */
    protected $_chunk_size;

    /**
     * Constructor.
     *
     * @param FilesystemStream $stream      A FilesystemStream object
     * @para integer           $chunk_size  The chunk size to use.
     */
    public function __construct(FilesystemStream $stream, $chunk_size = 8129)
    {
        $this->_chunk      = '';
        $this->_chunk_size = $chunk_size;
        $this->__stream = $stream;
    }

    /**
     * Seeks to a given position in the stream
     *
     * @param int $position
     * @throws \OutOfBoundsException If the position is not seekable.
     */
    public function seek($position)
    {
        if ($position > $this->getStream()->getSize()) {
            throw new \OutOfBoundsException('Invalid seek position ('.$position.')');
        }

        $this->getStream()->seek($position, SEEK_SET);
    }

    /**
     * Read data from the stream and advance the pointer
     *
     * @return string
     */
    public function current()
    {
        return $this->_chunk;
    }

    /**
     * Returns the current position of the stream read/write pointer
     *
     * @return bool|int|mixed
     */
    public function key()
    {
        return $this->getStream()->peek();
    }

    /**
     * Move to the next chunk
     *
     * @return void
     */
    public function next()
    {
        $this->_chunk = $this->getStream()->read($this->_chunk_size($this->getChunkSize()));
    }

    /**
     * Rewind to the beginning of the stream
     *
     * @return void
     */
    public function rewind()
    {
        $this->getStream()->rewind();
    }

    /**
     * Checks if current position is valid
     *
     * @return bool
     */
    public function valid()
    {
        return !$this->getStream()->eof();
    }

    /**
     * Get the chunk size
     *
     * @return integer
     */
    public function getChunkSize()
    {
        return $this->_chunk_size;
    }

    /**
     * Get the stream object
     *
     * @return FilesystemStream
     */
    public function getStream()
    {
        return $this->__stream;
    }
}