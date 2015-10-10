<?php
/**
 * Created by PhpStorm.
 * User: nguyenvanduocit
 * Date: 10/9/2015
 * Time: 9:16 PM
 */

namespace FaceSDK;


use FaceSDK\Exception\FaceAPIException;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Ring\Exception\RingException;
use Psr\Http\Message\ResponseInterface;

class FaceClient
{
    /**
     * @const int The timeout in seconds for a normal request.
     */
    const DEFAULT_REQUEST_TIMEOUT = 60;
    /** @var  string */
    protected $apiUrl;
    /** @var  Client */
    protected $client;

    /**
     * FaceClient constructor.
     *
     * @param string $apiUrl
     * @param Client $client
     */
    public function __construct($apiUrl)
    {
        $this->apiUrl = $apiUrl;
        $this->client = new Client(
            [
                'base_uri' => $this->apiUrl,
            ]
        );
    }

    /**
     * @param FaceRequest $request
     *
     * @return FaceResponse
     * @throws FaceAPIException
     */
    public function sendRequest(FaceRequest $request)
    {
        list($endpoint, $method, $options) = $this->prepareRequestMessage($request);
        // Since file uploads can take a while, we need to give more time for uploads
        try {
            $rawResponse = $this->client->request($method, $endpoint, $options);
        } catch (RequestException $e) {
            $rawResponse = $e->getResponse();
            if ($e->getPrevious() instanceof RingException || ! $rawResponse instanceof ResponseInterface) {
                throw new FaceAPIException($e->getMessage(), $e->getCode());
            }
        }

        $returnResponse = new FaceResponse(
            $request,
            $rawResponse->getBody()->getContents(),
            $rawResponse->getStatusCode(),
            $rawResponse->getHeaders()
        );

        if ($returnResponse->isError()) {
            throw $returnResponse->getThrownException();
        }

        return $returnResponse;

    }

    /**
     * Prepares the request for sending to the client handler.
     *
     * @param FaceRequest $request
     *
     * @return array
     */
    public function prepareRequestMessage(FaceRequest $request)
    {
        $options = [];
        if ($request->containsFileUploads()) {
            $requestBody = $request->getMultipartBody();
            $request->setHeader(
                [
                    'Content-Type' => 'multipart/form-data; boundary='.$requestBody->getBoundary(),
                ]
            );
        } else {
            $requestBody = $request->getUrlEncodedBody();
            $request->setHeader(
                [
                    'Content-Type' => 'application/x-www-form-urlencoded',
                ]
            );
        }
        $options['body']    = $requestBody->getBody();
        $options['headers'] = $request->getHeader();
        $options['timeout'] = static::DEFAULT_REQUEST_TIMEOUT;

        return [
            $request->getEndpoint(),
            $request->getMethod(),
            $options,
        ];
    }
}