<?php declare(strict_types=1);
namespace Phan\Language;

use \Phan\CodeBase\File;
use \Phan\Config;

/**
 * An object representing the context in which any
 * structural element (such as a class or method) lives.
 */
class FileRef implements \Serializable
{

    /**
     * @var string
     * The path to the file in which this element is defined
     */
    protected $file = 'internal';

    /**
     * @var int
     * The starting line number of the element within the $file
     */
    protected $line_number_start = 0;

    /**
     * @var int
     * The ending line number of the element within the $file
     */
    protected $line_number_end = 0;

    /**
     * @param string $file
     * The path to the file in which this element is defined
     *
     * @return Context
     * This context with the given value is returned
     */
    public function withFile(string $file) : FileRef
    {
        $context = clone($this);
        $context->file = $file;
        return $context;
    }

    /**
     * @return string
     * The path to the file in which the element is defined
     */
    public function getFile() : string
    {
        return $this->file;
    }

    /**
     * @return string
     * The path of the file relative to the project
     * root directory
     */
    public function getProjectRelativePath() : string
    {
        $cwd_relative_path = $this->file;

        // Get a path relative to the project root
        $path = str_replace(
            Config::get()->getProjectRootDirectory(),
            '',
            realpath($cwd_relative_path) ?: $cwd_relative_path
        );

        // Strip any beginning directory separators
        if (0 === ($pos = strpos($path, DIRECTORY_SEPARATOR))) {
            $path = substr($path, $pos + 1);
        }

        return $path;
    }

    /**
     * @return bool
     * True if this object is internal to PHP
     */
    public function isInternal() : bool
    {
        return ('internal' === $this->getFile());
    }

    /**
     * @var int $line_number
     * The starting line number of the element within the file
     *
     * @return Context
     * This context with the given value is returned
     */
    public function withLineNumberStart(int $line_number) : FileRef
    {
        $this->line_number_start = $line_number;
        return $this;
    }

    /*
     * @return int
     * The starting line number of the element within the file
     */
    public function getLineNumberStart() : int
    {
        return $this->line_number_start;
    }

    /**
     * @param int $line_number
     * The ending line number of the element within the $file
     *
     * @return Context
     * This context with the given value is returned
     */
    public function withLineNumberEnd(int $line_number) : FileRef
    {
        $this->line_number_end = $line_number;
        return $this;
    }

    /**
     * Get a string representation of the context
     *
     * @return string
     */
    public function __toString() : string
    {
        return $this->file . ':' . $this->line_number_start;
    }

    public function serialize()
    {
        return (string)$this;
    }

    public function unserialize($serialized)
    {
        $map = explode(':', $serialized);
        $this->file = $map[0];
        $this->line_number_start = (int)$map[1];
        $this->line_number_end = (int)($map[2] ?? 0);
    }
}
