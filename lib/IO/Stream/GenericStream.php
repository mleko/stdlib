<?php
/**
 * Created on
 * Date: 29/07/15
 * Time: 15:52
 */

namespace Mleko\Stdlib\IO\Stream;


class GenericStream implements \Mleko\Stdlib\IO\InputStream, \Mleko\Stdlib\IO\OutputStream
{

    /** @var resource */
    private $handle;

    /** @var bool */
    private $closed;

    /**
     * @param resource|string $handle
     * @param null|string $mode Required when $handle is string stream definition. Available modes http://php.net/manual/function.fopen.php
     * @throws StreamException
     */
    function __construct($handle, $mode = null)
    {
        if (is_string($handle)) {
            if (!$mode) {
                throw new StreamException("Mode is required for string stream definitions");
            }

            $handle = fopen($handle, $mode);
            if (false === $handle) {
                $error = error_get_last();
                throw new StreamException("Failed to open stream: " . $error['message']);
            }
        }
        $this->handle = $handle;
        $this->closed = false;
    }

    /**
     * @inheritDoc
     */
    public function read($length = null)
    {
        return fread($this->handle, $length);
    }

    /**
     * @inheritDoc
     */
    public function write($data, $length = null)
    {
        $length = (int)($length ?: strlen($data));
        return fwrite($this->handle, $data, $length) === $length;
    }

    /**
     * @inheritDoc
     */
    public function flush()
    {
        return fflush($this->handle);
    }

    /**
     * @inheritDoc
     */
    public function close($flush = true)
    {
        if ($this->isClosed()) {
            return true;
        }
        $this->closed = true;
        if ($flush && false === $this->flush()) {
            return false;
        }
        return fclose($this->handle);
    }

    /**
     * @inheritDoc
     */
    public function isClosed()
    {
        return $this->closed;
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
        return $this->handle;
    }

}
