<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\RoleResource;
use App\Models\Role;
use Illuminate\Http\JsonResponse;

class RoleController extends Controller
{
    /**
     * Get all roles.
     */
    public function index(): JsonResponse
    {
        return response()->json(RoleResource::collection(Role::all()));
    }
}
