<?php
/**
 * Created on
 * Date: 29/07/15
 * Time: 15:52
 */

namespace Mleko\Stdlib\IO\Stream;


class GenericStream implements \Mleko\Stdlib\IO\InputStream, \Mleko\Stdlib\IO\OutputStream
{
    /** @var string[]|null */
    private $definition = null;

    /** @var resource|null */
    private $handle = null;

    /** @var bool */
    private $closed;

    /**
     * @param resource|string $handle
     * @param null|string $mode Required when $handle is string stream definition. Available modes http://php.net/manual/function.fopen.php
     * @param bool $open Should stream be opened eagerly. Usable when $handle is string definition.
     * @throws StreamException
     */
    function __construct($handle, $mode = null, $open = false)
    {
        if (is_string($handle)) {
            if (!$mode) {
                throw new StreamException("Mode is required for string stream definitions");
            }

            $this->definition = [$handle, $mode];
            if ($open) {
                $this->open();
            }
        } elseif (is_resource($handle)) {
            $this->handle = $handle;
        } else {
            throw new StreamException("Invalid stream definition");
        }
        $this->closed = false;
    }

    /**
     * @inheritDoc
     */
    public function read($length = null)
    {
        return fread($this->getHandle(), $length);
    }

    /**
     * @inheritDoc
     */
    public function write($data, $length = null)
    {
        $length = (int)($length ?: strlen($data));
        return fwrite($this->getHandle(), $data, $length) === $length;
    }

    /**
     * @inheritDoc
     */
    public function flush()
    {
        return fflush($this->getHandle());
    }

    /**
     * @inheritDoc
     */
    public function close($flush = true)
    {
        if ($this->isClosed()) {
            return true;
        }
        if ($flush && false === $this->flush()) {
            return false;
        }
        return $this->closed = fclose($this->getHandle());
    }

    /**
     * @inheritDoc
     */
    public function isClosed()
    {
        return $this->closed;
    }

    /**
     * @inheritdoc
     */
    public function endOfStream()
    {
        return feof($this->getHandle());
    }

    /**
     * @inheritdoc
     */
    public function exists()
    {
        if ($this->isOpened()) {
            throw new StreamException("Cannot check existence of resource if stream was already opened");
        }
        return file_exists($this->definition[0]);
    }

    /**
     * @return bool
     */
    public function isOpened()
    {
        return null !== $this->handle;
    }

    /**
     * @return resource
     * @throws StreamException
     */
    protected function getHandle()
    {
        if ($this->closed) {
            throw new StreamException("Stream is closed");
        }
        if (!$this->isOpened()) {
            $this->open();
        }
        return $this->handle;
    }

    private function open()
    {
        if($this->isOpened()){
            throw new StreamException("Stream was already opened");
        }
        $handle = fopen($this->definition[0], $this->definition[1]);
        $this->handle = $handle;
        if (false === $handle) {
            $error = error_get_last();
            throw new StreamException("Failed to open stream: " . $error['message']);
        }
    }
}
