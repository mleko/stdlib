<?php
/**
 * Created on
 * Date: 30/07/15
 * Time: 10:09
 */

namespace Mleko\Stdlib\IO;


interface OutputStream extends Stream
{
    /**
     * @param string $data
     * @param null|int $length
     * @return int|false 
     */
    public function write($data, $length = null);

    /**
     * @return boolean
     */
    public function flush();

    /**
     * @param boolean $flush
     * @return boolean
     */
    public function close($flush = true);

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