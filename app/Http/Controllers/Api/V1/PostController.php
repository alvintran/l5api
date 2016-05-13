<?php namespace Nht\Http\Controllers\Api\V1;

use Illuminate\Http\Request;
use Nht\Http\Controllers\Api\ApiController;
use Nht\Http\Transformers\PostTransformer;
use Illuminate\Validation\ValidationException;
use Nht\Post;
use Validator;

class PostController extends ApiController
{
    protected $model;
    protected $transformer;
    protected $validationRules = [
        'title' => 'required'
    ];

    public function __construct(Post $post, PostTransformer $transformer)
    {
        $this->model = $post;
        $this->transformer = $transformer;
    }

    public function index()
    {
    	return $this->listResponse($this->model->all(), $this->transformer);
    }

    public function show($id)
    {
        if ($post = $this->model->find($id))
        {
            return $this->showResponse($post, $this->transformer);
        }
        return $this->notFoundResponse();
    }

    public function store(Request $request)
    {
        $v = Validator::make($request->all(), $this->validationRules);
        if ($v->fails())
        {
            throw new ValidationException($v);
        }
        $post = $this->model->create($request->all());
        return $this->showResponse($post, $this->transformer);
    }

    public function update(Request $request, $id)
    {
    	if (!$post = $this->model->find($id))
    	{
    		return $this->notFoundResponse();
    	}

        $v = Validator::make($request->all(), $this->validationRules);
        if ($v->fails())
        {
            throw new ValidationException($v);
        }
    	$post->fill($request->all())->save();
    	return $this->showResponse($post, $this->transformer);
    }

    public function destroy($id)
    {
    	if (!$post = $this->model->find($id))
    	{
    		return $this->notFoundResponse();
    	}
    	$post->delete();
    	return $this->deletedResponse();
    }
}
