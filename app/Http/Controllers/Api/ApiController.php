<?php

namespace Nht\Http\Controllers\Api;

use Nht\Http\Controllers\Controller;

abstract class ApiController extends Controller
{
    protected $statusCode = \Illuminate\Http\Response::HTTP_OK;

    protected function showResponse($data, $transformer = null)
    {
        if ($transformer)
        {
            $fractal = new \League\Fractal\Manager();
            $data = new \League\Fractal\Resource\Item($data, $transformer);
            $data = $fractal->createData($data)->toArray();
        }
        return $this->response($data);
    }

    protected function listResponse($data, $transformer = null)
    {
        if ($transformer)
        {
            $fractal = new \League\Fractal\Manager();
            $data = new \League\Fractal\Resource\Collection($data, $transformer);
            $data = $fractal->createData($data)->toArray();
        }
        return $this->response($data);
    }

    protected function notFoundResponse()
    {
        return $this->setStatusCode(404)->response([
            'status' => 'error',
            'data' => 'Resource Not Found',
            'message' => 'Not Found'
        ]);
    }

    protected function deletedResponse()
    {
        return $this->setStatusCode(204)->response([
            'status' => 'success',
            'data' => [],
            'message' => 'Resource deleted'
        ]);
    }

    protected function clientErrorResponse($data)
    {
        return $this->setStatusCode(422)->response([
            'status' => 'error',
            'data' => $data,
            'message' => 'Unprocessable entity'
        ]);
    }

    protected function setStatusCode($code)
    {
        $this->statusCode = $code;
        return $this;
    }

    protected function getStatusCode()
    {
        return $this->statusCode;
    }

    protected function response($data, $headers = [])
    {
        return response()->json($data, $this->getStatusCode(), $headers);
    }
}
