<?php

namespace Mocker\Resource;

use League\Fractal\{
    Resource\ResourceInterface,
    Manager as ResourceManager
};

use Mocker\{
    Resource\Factory as ResourceFactory,
    Transformer\Factory as TransformerFactory
};

class Formatter implements FormatterInterface
{
    /**
     * @var ResourceManager
     */
    private $resourceManager;

    /**
     * @var Factory
     */
    private $resourceFactory;

    /**
     * @var TransformerFactory
     */
    private $transformerFactory;

    /**
     * @var
     */
    private $transformer;

    /**
     * Formatter constructor.
     * @param ResourceManager $resourceManager
     * @param Factory $resourceFactory
     * @param TransformerFactory $transformerFactory
     */
    public function __construct(
        ResourceManager $resourceManager,
        ResourceFactory $resourceFactory,
        TransformerFactory $transformerFactory
    )
    {
        $this->resourceManager = $resourceManager;
        $this->resourceFactory = $resourceFactory;
        $this->transformerFactory = $transformerFactory;
    }

    /**
     * @param string $transformer
     * @return $this
     */
    public function setTransformer(string $transformer) : Formatter
    {
        $this->transformer = $this->transformerFactory->getInstance($transformer);

        return $this;
    }

    /**
     * @param array $data
     * @return array
     */
    public function formatItem(array $data) : array
    {
        $resource = $this->resourceFactory->getInstance(ResourceFactory::ITEM, $data, $this->transformer);

        return $this->format($resource);
    }

    /**
     * @param array $data
     * @return array
     */
    public function formatCollection(array $data) : array
    {
        $resource = $this->resourceFactory->getInstance(ResourceFactory::COLLECTION, $data, $this->transformer);

        return $this->format($resource);
    }

    /**
     * @param ResourceInterface $resource
     * @return array
     */
    private function format(ResourceInterface $resource) : array
    {
        return $this->resourceManager->createData($resource)->toArray();
    }
}