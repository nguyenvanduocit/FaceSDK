<?php
namespace FaceSDK\Node;

use FaceSDK\FaceRequest;

/**
 * Class Edge
 *
 * @package Facebook
 */
class Edge extends Collection
{
    /**
     * @var FaceRequest The original request that generated this data.
     */
    protected $request;

    /**
     * @var array An array of Graph meta data like pagination, etc.
     */
    protected $metaData = [];


    /**
     * Init this collection of Node's.
     *
     * @param FaceRequest $request      The original request that generated this data.
     * @param array       $data         An array of Node's.
     * @param string|null $subclassName The subclass of the child Node's.
     *
     * @internal param null|string $parentEdgeEndpoint The parent Graph edge endpoint that generated the list.
     */
    public function __construct(FaceRequest $request, array $data = [], $subclassName = null)
    {
        $this->request      = $request;
        $this->subclassName = $subclassName;

        parent::__construct($data);
    }

    /**
     * Gets the subclass name that the child Node's are cast as.
     *
     * @return string|null
     */
    public function getSubClassName()
    {
        return $this->subclassName;
    }
}
