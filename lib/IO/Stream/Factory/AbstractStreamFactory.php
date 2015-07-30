<?php
/**
 * Created on
 * Date: 30/07/15
 * Time: 09:14
 */

namespace Mleko\Stdlib\IO\Stream\Factory;


abstract class AbstractStreamFactory implements \Mleko\Stdlib\IO\Stream\StreamFactory
{
    /**
     * Stream create mode.
     * 
     * @var string
     */
    private $createMode = 'x';

    /**
     * AbstractStreamFactory constructor.
     * @param string $createMode
     */
    public function __construct($createMode)
    {
        $this->createMode = $createMode;
    }


    /**
     * @inheritDoc
     */
    public function createInputStream($definition)
    {
        return $this->createStream($definition, "r");
    }

    /**
     * @inheritDoc
     */
    public function createOutputStream($definition)
    {
        return $this->createStream($definition, $this->createMode);
    }

    /**
     * @param mixed $components
     * @param string $mode Stream mode. Available modes http://php.net/manual/function.fopen.php
     * @return \Mleko\Stdlib\IO\Stream
     */
    abstract protected function createStream($components, $mode);


}