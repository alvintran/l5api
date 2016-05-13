<?php

namespace Nht\Http\Requests;

use Nht\Http\Requests\Request;
use Illuminate\Contracts\Validation\Validator;

class PostRequest extends Request
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    // public function formatErrors(Validator $validator)
    // {
    //     return $validator->errors()->all();
    // }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'title' => 'required'
        ];
    }
}
