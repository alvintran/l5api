<?php namespace Nht\Http\Controllers\Api\V1;

use Illuminate\Http\Request;
use Nht\Http\Requests\PostRequest;
use Nht\Post;
use Nht\Http\Controllers\Api\ApiController;
use Nht\Http\Transformers\PostTransformer;

class PostController extends ApiController
{
    protected $transformer;

    public function __construct(PostTransformer $transformer)
    {
        $this->transformer = $transformer;
    }

    public function index()
    {
    	return $this->listResponse(Post::all(), $this->transformer);
    }

    public function show($id)
    {
        if ($post = Post::find($id))
        {
            return $this->showResponse($post, $this->transformer);
        }
        return $this->notFoundResponse();
    }

    public function store(PostRequest $request)
    {
    	$post = Post::create($request->all());
        return $this->showResponse($post, $this->transformer);
    }

    public function update(PostRequest $request, $id)
    {
    	if (!$post = Post::find($id))
    	{
    		return $this->notFoundResponse();
    	}

    	$post->fill($request->all())->save();
    	return $this->showResponse($post, $this->transformer);
    }

    public function destroy($id)
    {
    	if (!$post = Post::find($id))
    	{
    		return $this->notFoundResponse();
    	}
    	$post->delete();
    	return $this->deletedResponse();
    }
}
