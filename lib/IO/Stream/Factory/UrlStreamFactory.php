<?php
/**
 * Created on
 * Date: 30/07/15
 * Time: 09:19
 */

namespace Mleko\Stdlib\IO\Stream\Factory;

/**
 * Class UrlStreamFactory
 * @package Mleko\StdLib\Stream\Factory
 *
 * Factory building Streams from components provided in format of http://php.net/manual/function.parse-url.php
 */
class UrlStreamFactory extends AbstractStreamFactory
{

    public $joinPath = true;
    public $joinQuery = true;
    private $default = [];
    /** @var \Psr\Log\LoggerInterface|null */
    private $logger;

    /**
     * UrlStreamFactory constructor.
     * @param string[] $components Default components values
     * @param string $createMode
     * @param bool $joinPath
     * @param bool $joinQuery
     * @param \Psr\Log\LoggerInterface $logger
     */
    public function __construct(array $components = [], $createMode = 'x', $joinPath = true, $joinQuery = true, \Psr\Log\LoggerInterface $logger = null)
    {
        $this->default = $components;
        $this->joinPath = $joinPath;
        $this->joinQuery = $joinQuery;
        $this->logger = $logger;

        parent::__construct($createMode);
    }

    /**
     * @param string|string[] $components path or array in format defined by http://php.net/manual/function.parse-url.php
     * @param string $mode Stream mode. Available modes http://php.net/manual/function.fopen.php
     * @param bool $write
     * @return \Mleko\Stdlib\IO\Stream
     * @throws \Mleko\Stdlib\IO\Stream\StreamException
     */
    protected function createStream($components, $mode, $write = false)
    {
        $url = $this->buildUrl($components);

        if (false === $url) {
            throw new  \Mleko\Stdlib\IO\Stream\StreamException("Failed to build stream url");
        }
        if ($write) {
            // Ignore failures as some streams are directory-less
            $this->createDirectory($url);
        }
        return new \Mleko\Stdlib\IO\Stream\GenericStream((string)$url, $mode);
    }

    /**
     * @param mixed $definition
     * @return \League\Url\UrlImmutable
     */
    private function buildUrl($definition)
    {
        if (is_string($definition)) {
            $definition = ['path' => $definition];
        }
        $url = \League\Url\UrlImmutable::createFromArray(array_merge($this->default, $definition));
        if ($this->joinPath && isset($definition['path']) && isset($this->default['path'])) {
            $path = new \League\Url\Components\Path($this->default['path']);
            $path->append($definition['path']);
            $url = $url->setPath($path);
        }
        if ($this->joinQuery && isset($definition['query']) && isset($this->default['query'])) {
            $query = new \League\Url\Components\Query($this->default['query']);
            $definitionQuery = new \League\Url\Components\Query($definition['query']);
            foreach ($definitionQuery as $k => $v) {
                $query[$k] = $v;
            }
            $url = $url->setQuery($query);
        }
        return $url;
    }

    /**
     * Try to create directory.
     *
     * @param \League\Url\UrlImmutable $url
     * @return boolean
     */
    private function createDirectory($url)
    {
        $path = $url->getPath();
        $pathArray = $path->toArray();
        $this->log(\Psr\Log\LogLevel::DEBUG, "Ensure directory exists", ['url' => $url]);
        array_pop($pathArray);
        $url = $url->setPath(new \League\Url\Components\Path($pathArray));
        $result = true;
        if (!file_exists((string)$url)) {
            $this->log(\Psr\Log\LogLevel::DEBUG, "Attempt directory creation", ['path' => (string)$url]);
            $result = @mkdir((string)$url, 0777, true);
            $this->log(\Psr\Log\LogLevel::DEBUG, $result ? "Created directory" : "Failed to create directory", ['path' => (string)$url]);
        }
        return $result;
    }

    private function log($level, $message, $context = [])
    {
        if ($this->logger) {
            $this->logger->log($level, $message, $context);
        }
    }

}
