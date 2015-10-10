<?php
/**
 * Created by PhpStorm.
 * User: nguyenvanduocit
 * Date: 10/9/2015
 * Time: 9:52 PM
 */

namespace FaceSDK;


use FaceSDK\Exception\FaceAPIException;
use FaceSDK\FileUpload\FaceFile;
use FaceSDK\HTTP\RequestBodyMultipart;
use FaceSDK\HTTP\RequestBodyUrlEncoded;
use FaceSDK\Url\UrlManipulator;

class FaceRequest
{
    /** @var  string */
    protected $method;
    /** @var  string */
    protected $endpoint;
    /** @var  array */
    protected $header = [];
    /** @var  array */
    protected $params = [];
    /** @var  string */
    protected $apiKey;
    /** @var  string */
    protected $apiSecret;
    /** @var  string */
    protected $apiUrl;
    /**
     * @var array The files to send with this request.
     */
    protected $files = [];

    /**
     * FaceRequest constructor.
     *
     * @param null   $apiKey
     * @param null   $apiSecret
     * @param null   $apiUrl
     * @param string $method
     * @param string $endpoint
     * @param array  $params
     *
     */
    public function __construct(
        $apiKey = null,
        $apiSecret = null,
        $apiUrl = null,
        $method = null,
        $endpoint = null,
        array $params = []
    ) {
        $this->setMethod($method);
        $this->setEndpoint($endpoint);
        $this->setApiKey($apiKey);
        $this->setApiSecret($apiSecret);
        $this->setApiUrl($apiUrl);
        $this->setParams($params);
    }

    /**
     * @return string
     */
    public function getMethod()
    {
        return $this->method;
    }

    /**
     * @param string $method
     */
    public function setMethod($method)
    {
        $this->method = $method;
    }

    /**
     * @return string
     */
    public function getEndpoint()
    {
        return $this->endpoint;
    }

    /**
     * Only return params on POST requests.
     *
     * @return array
     */
    public function getPostParams()
    {
        if ($this->getMethod() === 'POST') {
            return $this->getParams();
        }

        return [];
    }

    /**
     * @param string $endpoint
     */
    public function setEndpoint($endpoint)
    {
        $this->endpoint = $endpoint;
    }

    /**
     * @return array
     */
    public function getHeader()
    {
        $headers = static::getDefaultHeaders();

        return array_merge($this->header, $headers);
    }

    /**
     * @param array $header
     */
    public function setHeader($header)
    {
        $this->header = array_merge($this->header, $header);
    }

    /**
     * @return array
     */
    public function getParams()
    {
        $params = $this->params;
        if($this->getApiKey() && $this->getApiSecret()){
            $params['api_key'] = $this->getApiKey();
            $params['api_secret'] = $this->getApiSecret();
        }
        return $params;
    }

    /**
     * Returns the body of the request as multipart/form-data.
     *
     * @return RequestBodyMultipart
     */
    public function getMultipartBody()
    {
        $params = $this->getPostParams();

        return new RequestBodyMultipart($params, $this->files);
    }

    /**
     * Returns the body of the request as URL-encoded.
     *
     * @return RequestBodyUrlEncoded
     */
    public function getUrlEncodedBody()
    {
        $params = $this->getPostParams();

        return new RequestBodyUrlEncoded($params);
    }

    /**
     * @param array $params
     */
    public function setParams($params)
    {
        $params = $this->sanitizeFileParams($params);
        $this->dangerouslySetParams($params);
    }
    /**
     * Validate that the HTTP method is set.
     *
     * @throws FaceAPIException
     */
    public function validateMethod()
    {
        if (!$this->method) {
            throw new FaceAPIException('HTTP method not specified.');
        }

        if (!in_array($this->method, ['GET', 'POST', 'DELETE'])) {
            throw new FaceAPIException('Invalid HTTP method specified.');
        }
    }
    /**
     * Add a file to be uploaded.
     *
     * @param string   $key
     * @param FaceFile $file
     */
    public function addFile($key, FaceFile $file)
    {
        $this->files[$key] = $file;
    }

    /**
     * Removes all the files from the upload queue.
     */
    public function resetFiles()
    {
        $this->files = [];
    }

    /**
     * Get the list of files to be uploaded.
     *
     * @return array
     */
    public function getFiles()
    {
        return $this->files;
    }

    /**
     * Let's us know if there is a file upload with this request.
     *
     * @return boolean
     */
    public function containsFileUploads()
    {
        return ! empty($this->files);
    }

    public function sanitizeFileParams(array $params)
    {
        foreach ($params as $key => $value) {
            if ($value instanceof FaceFile) {
                $this->addFile($key, $value);
                unset($params[$key]);
            }
        }

        return $params;
    }

    /**
     * Set the params for this request without filtering them first.
     *
     * @param array $params
     *
     * @return $this
     */
    public function dangerouslySetParams(array $params = [])
    {
        $this->params = array_merge($this->params, $params);

        return $this;
    }

    /**
     * @return string
     */
    public function getApiKey()
    {
        return $this->apiKey;
    }

    /**
     * @param string $apiKey
     */
    public function setApiKey($apiKey)
    {
        $this->apiKey = $apiKey;
    }

    /**
     * @return string
     */
    public function getApiSecret()
    {
        return $this->apiSecret;
    }

    /**
     * @param string $apiSecret
     */
    public function setApiSecret($apiSecret)
    {
        $this->apiSecret = $apiSecret;
    }

    /**
     * @return string
     */
    public function getApiUrl()
    {
        return $this->apiUrl;
    }
    /**
     * @param string $apiUrl
     */
    public function setApiUrl($apiUrl)
    {
        $this->apiUrl = $apiUrl;
    }

    public function getUrl()
    {
        $this->validateMethod();
        $url = $this->getApiUrl().$this->getEndpoint();
        if ($this->getMethod() !== 'POST') {
            $params = $this->getParams();
            $url = UrlManipulator::appendParamsToUrl($url, $params);
        }
        return $url;
    }

    /**
     * Return the default headers that every request should use.
     *
     * @return array
     */
    public static function getDefaultHeaders()
    {
        return [
            'User-Agent'      => 'face-api-php-'.FaceSDK::VERSION,
            'Accept-Encoding' => 'application/json',
        ];
    }
}