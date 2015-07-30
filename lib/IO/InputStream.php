<?php
/**
 * Created on
 * Date: 30/07/15
 * Time: 10:08
 */

namespace Mleko\Stdlib\IO;


interface InputStream extends Stream
{
    public function read($length = null);
}