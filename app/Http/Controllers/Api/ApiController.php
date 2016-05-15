<?php namespace Nht\Http\Controllers\Api;

use Illuminate\Http\Request;
use Nht\Http\Controllers\Controller;
use Illuminate\Http\Response as HttpResponse;
use League\Fractal\Manager;
use League\Fractal\Resource\Item;
use League\Fractal\Resource\Collection;

abstract class ApiController extends Controller
{
    /**
     * Status code for response
     * @var int
     */
    protected $statusCode = HttpResponse::HTTP_OK;

    /**
     * Show response with fractal resource for an Item
     * @param  mix $data            Resource
     * @param  FractalTransformer $transformer
     * @return json
     */
    protected function showResponse($data, $transformer = null)
    {
        if ($transformer)
        {
            $fractal = new Manager;
            $resource = new Item($data, $transformer);
            $data = $fractal->createData($resource)->toArray();
        }
        return $this->response($data);
    }

    /**
     * Show response with fractal resource for a Collection
     * @param  mix $data            Resource
     * @param  FractalTransformer $transformer
     * @return json
     */
    protected function listResponse($data, $transformer = null)
    {
        if ($transformer)
        {
            $fractal = new Manager;
            $resource = new Collection($data, $transformer);
            $data = $fractal->createData($resource)->toArray();
        }
        return $this->response($data);
    }

    /**
     * Not found response
     * @param  string $message
     * @return json
     */
    protected function notFoundResponse($message = 'Resource Not Found')
    {
        return $this->setStatusCode(404)->response([
            'status' => 'error',
            'data' => [],
            'message' => $message
        ]);
    }

    /**
     * Delete resource response
     * @return json
     */
    protected function deletedResponse()
    {
        return $this->setStatusCode(204)->response([
            'status' => 'success',
            'data' => [],
            'message' => 'Resource deleted'
        ]);
    }

    /**
     * Client error response
     * @param  array $data
     * @return json
     */
    protected function clientErrorResponse($data)
    {
        return $this->setStatusCode(422)->response([
            'status' => 'error',
            'data' => $data,
            'message' => 'Unprocessable entity'
        ]);
    }

    /**
     * Validate incoming request
     * @param  Request $request
     * @param  array   $rules
     * @return null|array
     */
    protected function validRequest(Request $request, $rules = [])
    {
        try {
            $this->validate($request, $rules);
        } catch(\Exception $e) {
            $response = ['form_validations' => $e->validator->errors(), 'exception' => $e->getMessage()];
            return $response;
        }
    }

    /**
     * Set status code for response
     * @param int $code Http status code
     */
    protected function setStatusCode($code)
    {
        $this->statusCode = $code;
        return $this;
    }

    /**
     * Get status code
     * @return int $code Http status code
     */
    protected function getStatusCode()
    {
        return $this->statusCode;
    }

    /**
     * Resource response
     * @param  array $data
     * @param  array  $headers
     * @return json
     */
    private function response($data, $headers = [])
    {
        return response()->json($data, $this->getStatusCode(), $headers);
    }
}
