<?php namespace Nht\Hocs\Helpers;

use League\Fractal\Manager;
use League\Fractal\Resource\Item as FItem;
use League\Fractal\Resource\Collection as FCollection;
use League\Fractal\TransformerAbstract;
use League\Fractal\Pagination\IlluminatePaginatorAdapter;
use Illuminate\Support\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\AbstractPaginator;

class NhtFractal
{
    protected $manager;
    protected $config;
    protected $transformer;

    public function __construct($config = [])
    {
        $this->config = $config;
        $this->manager = new Manager;
    }

    public function getData($data, $transformer)
    {
        $this->setTransformer($transformer);
        if ( ! $this->getTransformer() instanceof TransformerAbstract)
        {
            return ['data' => []];
        }

        if ($data instanceof Model) {
            return $this->item($data);
        } else if ($data instanceof Collection) {
            return $this->collection($data);
        } else if ($data instanceof AbstractPaginator) {
            return $this->paginate($data);
        } else {
            return $data->toArray();
        }
    }

    public function item($data)
    {
        $resource = new FItem($data, $this->getTransformer());
        return $this->manager->createData($resource)->toArray();
    }

    public function collection($data)
    {
        $resource = new FCollection($data, $this->getTransformer());
        return $this->manager->createData($resource)->toArray();
    }

    public function paginate($paginator)
    {
        $data = $paginator->getCollection();
        $queryParams = array_diff_key($_GET, array_flip(['page']));
        $paginator->appends($queryParams);
        $resource = new FCollection($data, $this->getTransformer());
        $resource->setPaginator(new IlluminatePaginatorAdapter($paginator));
        return $this->manager->createData($resource)->toArray();
    }

    public function setTransformer($transformer)
    {
        $this->transformer = $transformer;
        return $this;
    }

    public function getTransformer()
    {
        return $this->transformer;
    }
}