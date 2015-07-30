<?php
/**
 * Created on
 * Date: 30/07/15
 * Time: 09:12
 */

namespace Mleko\Stdlib\IO\Stream;


interface StreamFactory
{
    /**
     * @param mixed $definition
     * @return \Mleko\Stdlib\IO\InputStream
     */
    public function createInputStream($definition);

    /**
     * @param mixed $definition
     * @return \Mleko\Stdlib\IO\OutputStream
     */
    public function createOutputStream($definition);
}