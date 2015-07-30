<?php
/**
 * Created on
 * Date: 30/07/15
 * Time: 10:06
 */
namespace Mleko\Stdlib\IO;

interface Stream
{
    public function close();

    public function isClosed();
}