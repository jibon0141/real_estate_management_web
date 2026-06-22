<?php

namespace App\Services;

use App\Models\User;

class DesignationService
{
    public function updateDesignation($sell = null){

        $user = User::where('id',$sell->user_id)->first();
    }

}
