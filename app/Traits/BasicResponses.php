<?php

namespace App\Traits;

trait BasicResponses
{

    /**
     * > This function returns an array with a status and data key
     *
     * @param bool status This is a boolean value that indicates whether the token was generated
     * successfully or not.
     * @param data The data you want to send to the client.
     *
     * @return array An array with a status and data key.
     */
    public function basicResponse(bool $status, $data): array
    {
        return [
            'status'    =>  $status,
            'data'      =>  $data
        ];
    }
}
