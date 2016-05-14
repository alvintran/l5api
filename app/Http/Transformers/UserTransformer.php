<?php namespace Nht\Http\Transformers;

use League\Fractal\TransformerAbstract;
use Nht\User;

class UserTransformer extends TransformerAbstract
{
    public function transform(User $user)
    {
        return [
            'id'      => $user->id,
            'title'   => $user->name,
            'created' => date('d/m/Y', strtotime($user->created_at)),
            'updated' => date('d/m/Y', strtotime($user->updated_at))
        ];
    }
}
