<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\EquipmentResource;
use App\Http\Resources\UserResource;
use App\Models\Equipment;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class TrashController extends Controller
{
    /**
     * Get all trashed records.
     */
    public function index(): JsonResponse
    {
        return response()->json([
            'equipment' => EquipmentResource::collection(Equipment::onlyTrashed()->with('category')->get()),
            'users' => UserResource::collection(User::onlyTrashed()->get()),
        ]);
    }

    /**
     * Restore a record.
     */
    public function restore(Request $request, string $type, string $id): JsonResponse
    {
        $model = match ($type) {
            'equipment' => Equipment::onlyTrashed()->findOrFail($id),
            'user' => User::onlyTrashed()->findOrFail($id),
            default => abort(404),
        };

        $model->restore();
        $model->update(['available' => 1]);

        return response()->json(['message' => 'Đã khôi phục thành công.']);
    }

    /**
     * Permanent delete a record.
     */
    public function forceDelete(string $type, string $id): JsonResponse
    {
        $model = match ($type) {
            'equipment' => Equipment::onlyTrashed()->findOrFail($id),
            'user' => User::onlyTrashed()->findOrFail($id),
            default => abort(404),
        };

        if ($type === 'user' && $model->isAdmin()) {
            return response()->json(['message' => 'Không thể xóa vĩnh viễn Admin!'], 422);
        }

        $model->forceDelete();

        return response()->json(['message' => 'Đã xóa vĩnh viễn thành công.']);
    }
}
