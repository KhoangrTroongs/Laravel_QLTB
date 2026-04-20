<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreEquipmentRequest;
use App\Http\Requests\UpdateEquipmentRequest;
use App\Http\Resources\EquipmentResource;
use App\Models\Equipment;
use App\Models\EquipmentUser;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class EquipmentController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $query = Equipment::with('category');

        // Filter by category
        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Search by name or model
        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('name', 'like', '%'.$request->search.'%')
                    ->orWhere('model', 'like', '%'.$request->search.'%');
            });
        }

        // Advanced Sort
        $sortField = $request->get('sort_by', 'id');
        $sortDir = $request->get('sort_order', 'desc');
        $allowed = ['id', 'name', 'model', 'status', 'category_id', 'created_at'];
        
        if (in_array($sortField, $allowed)) {
            $query->orderBy($sortField, $sortDir === 'asc' ? 'asc' : 'desc');
        } else {
            $query->latest();
        }

        $equipment = $query->paginate(10);

        return EquipmentResource::collection($equipment)->response();
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreEquipmentRequest $request): JsonResponse
    {
        $data = $request->validated();
        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('equipment', 'public');
        }

        if ($request->has('specs')) {
            $data['spec'] = $request->specs;
        }

        $equipment = Equipment::create($data);

        return response()->json(new EquipmentResource($equipment->load('category')), 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Equipment $equipment): JsonResponse
    {
        $equipment->load(['category', 'users' => function ($q) {
            $q->withTrashed()->orderBy('equipment_users.id', 'desc');
        }]);

        return response()->json(new EquipmentResource($equipment));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateEquipmentRequest $request, Equipment $equipment): JsonResponse
    {
        $data = $request->validated();
        if ($request->hasFile('image')) {
            if ($equipment->image) {
                Storage::disk('public')->delete($equipment->image);
            }
            $data['image'] = $request->file('image')->store('equipment', 'public');
        }

        if ($request->has('specs')) {
            $data['spec'] = $request->specs;
        }

        $equipment->update($data);

        return response()->json(new EquipmentResource($equipment->load('category')));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Equipment $equipment): JsonResponse
    {
        $isBorrowed = $equipment->users()
            ->wherePivot('status', EquipmentUser::STATUS_BORROWING)
            ->exists();

        if ($isBorrowed) {
            return response()->json(['message' => 'Không thể xóa thiết bị này vì đang có nhân viên mượn và chưa trả!'], 422);
        }

        $equipment->update(['available' => 0]);
        $equipment->delete();

        return response()->json(null, 204);
    }

    /**
     * Custom endpoint: Get available equipment for borrowing.
     */
    public function available(Request $request): JsonResponse
    {
        $query = Equipment::with('category')->where('status', 1)->where('available', 1);

        $query->whereDoesntHave('users', function ($q) {
            $q->where('equipment_users.status', EquipmentUser::STATUS_BORROWING);
        });

        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('name', 'like', '%'.$request->search.'%')
                    ->orWhere('model', 'like', '%'.$request->search.'%');
            });
        }

        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        $availableEquipment = $query->latest()->paginate(12);

        return EquipmentResource::collection($availableEquipment)->response();
    }
}
