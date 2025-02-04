<?php

namespace App\Http\Controllers\Api\v1;

use App\Domain\User\Services\UserService;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class MeController extends Controller
{
    private UserService $service;

    public function __construct(UserService $service)
    {
        $this->service = $service;
    }

    public function show() {}

    public function changePassword() {}

    public function update() {}

    public function statistics(){

    }
}
