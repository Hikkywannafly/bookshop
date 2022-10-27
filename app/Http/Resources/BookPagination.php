<?php

namespace App\Http\Resources;

use App\Http\Resources\BaseResource;

class BookPagination extends BaseResource
{

    public function toArray($request)
    {

        return [
            'data' => 'heelo',
        ];
    }
}
