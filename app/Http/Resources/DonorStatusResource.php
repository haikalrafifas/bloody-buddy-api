<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Auth;

class DonorStatusResource extends JsonResource
{
    public function toArray(Request $request)
    {
        $isAdmin = Auth::user()->is_admin;

        $data = [
            'name' => $this->name,
            'description' => $this->description,
            'actions' => '',
        ];

        if ( $this->name === 'Waiting List' ) $data['actions'] = $isAdmin ? 'approve,reject' : 'cancel';
        if ( $this->name === 'Approved' ) $data['actions'] = $isAdmin ? 'start' : 'cancel';
        if ( $this->name === 'Ongoing' ) $data['actions'] = $isAdmin ? 'done' : '';

        return $data;
    }
}
