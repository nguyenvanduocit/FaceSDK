<?php
/**
 * Created by PhpStorm.
 * User: nguyenvanduocit
 * Date: 10/10/2015
 * Time: 12:37 PM
 */

namespace FaceSDK\FileUpload;


use FaceSDK\Exception\FaceAPIException;

class FaceFile
{
    /**
     * @var string The path to the file on the system.
     */
    protected $path;

    /**
     * @var resource The stream pointing to the file.
     */
    protected $stream;

    /**
     * Creates a new FacebookFile entity.
     *
     * @param string $filePath
     *
     * @throws FaceAPIException
     */
    public function __construct($filePath)
    {
        $this->path = $filePath;
        $this->open();
    }

    /**
     * Closes the stream when destructed.
     */
    public function __destruct()
    {
        $this->close();
    }

    /**
     * Opens a stream for the file.
     *
     * @throws FaceAPIException
     */
    public function open()
    {
        if (!$this->isRemoteFile($this->path) && !is_readable($this->path)) {
            throw new FaceAPIException('Failed to create FacebookFile entity. Unable to read resource: ' . $this->path . '.');
        }

        $this->stream = fopen($this->path, 'r');

        if (!$this->stream) {
            throw new FaceAPIException('Failed to create FacebookFile entity. Unable to open resource: ' . $this->path . '.');
        }
    }

    /**
     * Stops the file stream.
     */
    public function close()
    {
        if (is_resource($this->stream)) {
            fclose($this->stream);
        }
    }

    /**
     * Return the contents of the file.
     *
     * @return string
     */
    public function getContents()
    {
        return stream_get_contents($this->stream);
    }

    /**
     * Return the name of the file.
     *
     * @return string
     */
    public function getFileName()
    {
        return basename($this->path);
    }

    /**
     * Return the mimetype of the file.
     *
     * @return string
     */
    public function getMimetype()
    {
        return Mimetypes::getInstance()->fromFilename($this->path) ?: 'text/plain';
    }

    /**
     * Returns true if the path to the file is remote.
     *
     * @param string $pathToFile
     *
     * @return boolean
     */
    protected function isRemoteFile($pathToFile)
    {
        return preg_match('/^(https?|ftp):\/\/.*/', $pathToFile) === 1;
    }
}