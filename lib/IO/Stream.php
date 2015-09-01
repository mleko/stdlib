<?php
/**
 * Created on
 * Date: 30/07/15
 * Time: 10:06
 */
namespace Mleko\Stdlib\IO;

interface Stream
{
    /**
     * @return boolean
     */
    public function close();

    /**
     * @return boolean
     */
    public function isClosed();

    /**
     * Check if reached end of stream
     *
     * @return boolean
     */
    public function endOfStream();

    /**
     * Check if destination resource exists
     *
     * @return boolean
     */
    public function exists();
}