<?php
/**
 * Created on
 * Date: 30/07/15
 * Time: 10:08
 */

namespace Mleko\Stdlib\IO;


interface InputStream extends Stream
{
    /**
     * @param integer|null $length
     * @return string|false
     */
    public function read($length = null);
}