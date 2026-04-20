<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreEquipmentUserRequest;
use App\Http\Requests\UpdateEquipmentUserRequest;
use App\Http\Resources\EquipmentResource;
use App\Http\Resources\EquipmentUserResource;
use App\Http\Resources\UserResource;
use App\Models\Equipment;
use App\Models\EquipmentUser;
use App\Models\User;
use App\Notifications\BorrowRequestResponse;
use App\Notifications\MissedBorrowRequest;
use App\Notifications\NewBorrowRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Notification;

class EquipmentUserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): JsonResponse
    {
        $query = EquipmentUser::with([
            'user' => fn ($q) => $q->withTrashed(),
            'equipment' => fn ($q) => $q->withTrashed(),
        ]);

        // Filter by status
        if ($request->filled('status') && $request->status !== '') {
            $query->where('equipment_users.status', $request->status);
        }

        // Filter by overdue
        if ($request->get('overdue') == 1) {
            $query->where('equipment_users.status', EquipmentUser::STATUS_BORROWING)
                ->where('equipment_users.hantra', '<', now());
        }

        // Search by user name or equipment name
        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->whereHas('user', fn ($u) => $u->withTrashed()->where('name', 'like', '%'.$request->search.'%'))
                    ->orWhereHas('equipment', fn ($e) => $e->withTrashed()->where('name', 'like', '%'.$request->search.'%'));
            });
        }

        // Date range filter
        if ($request->filled('from_date')) {
            $query->whereDate('equipment_users.ngaymuon', '>=', $request->from_date);
        }
        if ($request->filled('to_date')) {
            $query->whereDate('equipment_users.ngaymuon', '<=', $request->to_date);
        }

        // Join to support sorting
        $query->select('equipment_users.*')
            ->leftJoin('users', 'equipment_users.user_id', '=', 'users.id')
            ->leftJoin('equipment', 'equipment_users.equipment_id', '=', 'equipment.id');

        // Sort logic
        $sortField = $request->get('sort_by', 'id');
        $sortDir = $request->get('sort_order', 'desc');

        $sortMapping = [
            'id' => 'equipment_users.id',
            'employee_id' => 'users.employee_id',
            'user_name' => 'users.name',
            'equipment_name' => 'equipment.name',
            'ngaymuon' => 'equipment_users.ngaymuon',
            'status' => 'equipment_users.status',
        ];

        $column = $sortMapping[$sortField] ?? 'equipment_users.id';
        $query->orderBy($column, $sortDir === 'asc' ? 'asc' : 'desc');

        $records = $query->paginate(10);

        return EquipmentUserResource::collection($records)->response();
    }

    /**
     * Store a newly created resource (Admin version).
     */
    public function store(StoreEquipmentUserRequest $request): JsonResponse
    {
        $data = $request->validated();
        $data['ngaymuon'] = $data['ngaymuon'] ?? now();
        $data['status'] = EquipmentUser::STATUS_BORROWING; // Admins create active loans by default

        $borrowRequest = EquipmentUser::create($data);

        return response()->json(new EquipmentUserResource($borrowRequest->load(['user', 'equipment'])), 201);
    }

    /**
     * Store a borrow request (Public/User version).
     */
    public function storeBorrow(Request $request, Equipment $equipment): JsonResponse
    {
        $validated = $request->validate([
            'description' => ['nullable', 'string', 'max:500'],
            'hantra' => ['nullable', 'date', 'after_or_equal:today'],
        ]);

        // Double check status
        $isBorrowed = EquipmentUser::where('equipment_id', $equipment->id)
            ->where('status', EquipmentUser::STATUS_BORROWING)->exists();

        if ($equipment->status != 1 || $isBorrowed || $equipment->available == 0) {
            return response()->json(['message' => 'Thiết bị này hiện không thể mượn.'], 422);
        }

        // Check if user already has a pending or active borrow for this
        $alreadyBorrowing = EquipmentUser::where('user_id', Auth::id())
            ->where('equipment_id', $equipment->id)
            ->whereIn('status', [EquipmentUser::STATUS_PENDING, EquipmentUser::STATUS_BORROWING])
            ->exists();

        if ($alreadyBorrowing) {
            return response()->json(['message' => 'Bạn đã có một yêu cầu mượn hoặc đang mượn thiết bị này rồi!'], 422);
        }

        $borrowRecord = EquipmentUser::create([
            'user_id' => Auth::id(),
            'equipment_id' => $equipment->id,
            'ngaymuon' => now(),
            'hantra' => $request->hantra,
            'status' => EquipmentUser::STATUS_PENDING,
            'description' => $request->description,
        ]);

        // Notify Admins
        $admins = User::whereHas('roles', fn ($q) => $q->where('name', 'admin'))->get();
        Notification::send($admins, new NewBorrowRequest($borrowRecord));

        return response()->json([
            'message' => 'Yêu cầu mượn thiết bị của bạn đã được gửi.',
            'data' => new EquipmentUserResource($borrowRecord->load(['user', 'equipment'])),
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(EquipmentUser $equipmentUser): JsonResponse
    {
        $equipmentUser->load([
            'user' => fn ($q) => $q->withTrashed(),
            'equipment' => fn ($q) => $q->withTrashed(),
        ]);

        return response()->json(new EquipmentUserResource($equipmentUser));
    }

    /**
     * Update the specified resource (Admin).
     */
    public function update(UpdateEquipmentUserRequest $request, EquipmentUser $equipmentUser): JsonResponse
    {
        $equipmentUser->update($request->validated());

        return response()->json(new EquipmentUserResource($equipmentUser->load(['user', 'equipment'])));
    }

    /**
     * Remove the specified resource.
     */
    public function destroy(EquipmentUser $equipmentUser): JsonResponse
    {
        if ($equipmentUser->status == EquipmentUser::STATUS_BORROWING) {
            return response()->json(['message' => 'Không thể xóa phiếu mượn này vì thiết bị chưa được hoàn trả!'], 422);
        }

        $equipmentUser->delete();

        return response()->json(null, 204);
    }

    /**
     * Queue management and auto-rejection logic.
     */
    public function queue(): JsonResponse
    {
        // Auto-reject expired ones
        $expiredRequests = EquipmentUser::where('status', EquipmentUser::STATUS_PENDING)
            ->where('hantra', '<', now())
            ->get();

        foreach ($expiredRequests as $request) {
            $request->update([
                'status' => EquipmentUser::STATUS_REJECTED,
                'description' => ($request->description ? $request->description."\n" : '').'[Hệ thống] Tự động từ chối do quá hạn chờ duyệt.',
            ]);
            $request->user->notify(new BorrowRequestResponse($request));

            $admins = User::whereHas('roles', fn ($q) => $q->whereIn('name', ['admin', 'editor']))->get();
            foreach ($admins as $admin) {
                $admin->notify(new MissedBorrowRequest($request));
            }
        }

        $queue = EquipmentUser::with(['user', 'equipment'])
            ->where('status', EquipmentUser::STATUS_PENDING)
            ->orderBy('created_at', 'asc')
            ->get();

        return response()->json([
            'data' => EquipmentUserResource::collection($queue),
            'count' => $queue->count(),
        ]);
    }

    /**
     * Approve a borrow request.
     */
    public function approve(EquipmentUser $equipmentUser): JsonResponse
    {
        if ($equipmentUser->status !== EquipmentUser::STATUS_PENDING) {
            return response()->json(['message' => 'Phiếu mượn này không ở trạng thái chờ duyệt!'], 422);
        }

        $isBorrowed = EquipmentUser::where('equipment_id', $equipmentUser->equipment_id)
            ->where('status', EquipmentUser::STATUS_BORROWING)
            ->exists();

        if ($isBorrowed) {
            return response()->json(['message' => 'Thiết bị này hiện đang được người khác mượn!'], 422);
        }

        $equipmentUser->update([
            'status' => EquipmentUser::STATUS_BORROWING,
            'ngaymuon' => now(),
            'hantra' => $equipmentUser->hantra ?? now()->addDays(14),
        ]);

        $equipmentUser->user->notify(new BorrowRequestResponse($equipmentUser));

        return response()->json([
            'message' => 'Đã duyệt yêu cầu mượn thiết bị!',
            'data' => new EquipmentUserResource($equipmentUser->fresh(['user', 'equipment'])),
        ]);
    }

    /**
     * Reject a borrow request.
     */
    public function reject(EquipmentUser $equipmentUser): JsonResponse
    {
        if ($equipmentUser->status !== EquipmentUser::STATUS_PENDING) {
            return response()->json(['message' => 'Phiếu mượn này không ở trạng thái chờ duyệt!'], 422);
        }

        $equipmentUser->update(['status' => EquipmentUser::STATUS_REJECTED]);
        $equipmentUser->user->notify(new BorrowRequestResponse($equipmentUser));

        return response()->json([
            'message' => 'Đã từ chối yêu cầu mượn thiết bị!',
            'data' => new EquipmentUserResource($equipmentUser->fresh(['user', 'equipment'])),
        ]);
    }

    /**
     * Confirm return of equipment.
     */
    public function return(EquipmentUser $equipmentUser): JsonResponse
    {
        if ($equipmentUser->status !== EquipmentUser::STATUS_BORROWING) {
            return response()->json(['message' => 'Phiếu mượn này không ở trạng thái đang mượn!'], 422);
        }

        $equipmentUser->update([
            'status' => EquipmentUser::STATUS_RETURNED,
            'ngaytra' => now(),
        ]);

        $equipmentUser->user->notify(new BorrowRequestResponse($equipmentUser));

        return response()->json([
            'message' => 'Đã xác nhận trả thiết bị thành công!',
            'data' => new EquipmentUserResource($equipmentUser->fresh(['user', 'equipment'])),
        ]);
    }

    /**
     * User's own borrows.
     */
    public function myBorrows(): JsonResponse
    {
        $records = Auth::user()->equipments()->orderBy('equipment_users.id', 'desc')->paginate(10);

        return EquipmentUserResource::collection($records)->response();
    }

    /**
     * Generate a report data.
     */
    public function report(Request $request): JsonResponse
    {
        $year = $request->get('year', now()->year);
        $users = User::withTrashed()->withCount(['equipments' => function ($q) use ($year) {
            $q->whereYear('equipment_users.ngaymuon', $year);
        }])->having('equipments_count', '>', 0)->get();

        $reportData = [];
        foreach ($users as $user) {
            $borrowed = EquipmentUser::with(['equipment' => fn ($q) => $q->withTrashed()])
                ->where('user_id', $user->id)
                ->whereYear('ngaymuon', $year)
                ->get()
                ->groupBy('equipment_id');

            $items = [];
            foreach ($borrowed as $equipmentId => $entries) {
                $items[] = [
                    'equipment' => new EquipmentResource($entries->first()->equipment),
                    'count' => $entries->count(),
                    'from' => $entries->min('ngaymuon'),
                    'to' => $entries->max('ngaymuon'),
                ];
            }
            $reportData[] = [
                'user' => new UserResource($user),
                'items' => $items,
            ];
        }

        return response()->json([
            'data' => $reportData,
            'year' => $year,
        ]);
    }
}
