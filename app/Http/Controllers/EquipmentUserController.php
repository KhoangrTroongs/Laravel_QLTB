<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreEquipmentUserRequest;
use App\Http\Requests\UpdateEquipmentUserRequest;
use App\Models\Equipment;
use App\Models\EquipmentUser;
use App\Models\User;
use App\Notifications\BorrowRequestResponse;
use App\Notifications\NewBorrowRequest;
use Carbon\Carbon;
use Illuminate\Http\Request;

class EquipmentUserController extends Controller
{
    public function index(Request $request)
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

        // Join để hỗ trợ sắp xếp theo tên NV và thiết bị
        $query->select('equipment_users.*')
            ->leftJoin('users', 'equipment_users.user_id', '=', 'users.id')
            ->leftJoin('equipment', 'equipment_users.equipment_id', '=', 'equipment.id');

        // Sort logic
        $sortField = $request->get('sort', 'id');
        $sortDir = $request->get('direction', 'desc');

        $sortMapping = [
            'id' => 'equipment_users.id',
            'employee' => 'users.employee_id',
            'name' => 'users.name',
            'equipment' => 'equipment.name',
            'date' => 'equipment_users.ngaymuon',
            'status' => 'equipment_users.status',
        ];

        $column = $sortMapping[$sortField] ?? 'equipment_users.id';
        $query->orderBy($column, $sortDir === 'asc' ? 'asc' : 'desc');

        $records = $query->paginate(10)->withQueryString();

        return view('equipment_users.index', compact('records'));
    }

    public function queue()
    {
        // 1. Tự động chuyển các đơn quá hạn (chưa duyệt mà đã qua hạn trả) sang Từ chối
        $expiredRequests = EquipmentUser::where('status', EquipmentUser::STATUS_PENDING)
            ->where('hantra', '<', now())
            ->get();

        foreach ($expiredRequests as $request) {
            $request->update([
                'status' => EquipmentUser::STATUS_REJECTED,
                'description' => ($request->description ? $request->description . "\n" : "") . "[Hệ thống] Tự động từ chối do quá hạn chờ duyệt."
            ]);

            // Thông báo cho người dùng
            $request->user->notify(new BorrowRequestResponse($request));

            // Thông báo cho Admin
            $admins = User::whereHas('roles', function($q) {
                $q->whereIn('name', ['admin', 'editor']);
            })->get();
            
            foreach ($admins as $admin) {
                $admin->notify(new \App\Notifications\MissedBorrowRequest($request));
            }
        }

        // 2. Lấy danh sách hàng đợi hiện tại (những đơn status = 0 và chưa quá hạn)
        $queue = EquipmentUser::with(['user', 'equipment'])
            ->where('status', EquipmentUser::STATUS_PENDING)
            ->orderBy('created_at', 'asc')
            ->get();

        return view('equipment_users.queue', compact('queue'));
    }

    public function create()
    {
        $users = User::where('status', 1)->where('available', 1)->orderBy('name')->get();

        // Chỉ lấy thiết bị đang hoạt động VÀ không có ai đang mượn (status = BORROWING) VÀ available = 1
        $equipment = Equipment::where('status', 1)
            ->where('available', 1)
            ->whereDoesntHave('users', function ($q) {
                $q->where('equipment_users.status', EquipmentUser::STATUS_BORROWING);
            })->orderBy('name')->get();

        return view('equipment_users.create', compact('users', 'equipment'));
    }

    public function store(StoreEquipmentUserRequest $request)
    {
        $borrowRequest = EquipmentUser::create($request->validated());

        // Notify Admins
        $admins = User::whereHas('roles', function($q) {
            $q->whereIn('name', ['admin', 'editor']);
        })->get();
        foreach ($admins as $admin) {
            $admin->notify(new NewBorrowRequest($borrowRequest));
        }

        return redirect()->route('equipment-users.index')
            ->with('success', 'Phiếu mượn thiết bị đã được tạo thành công!');
    }

    public function show(EquipmentUser $equipmentUser)
    {
        $equipmentUser->load([
            'user' => fn ($q) => $q->withTrashed(),
            'equipment' => fn ($q) => $q->withTrashed(),
        ]);

        return view('equipment_users.show', compact('equipmentUser'));
    }

    public function edit(EquipmentUser $equipmentUser)
    {
        $users = User::where('status', 1)->where('available', 1)->orderBy('name')->get();

        // Lấy thiết bị rảnh HOẶC chính là thiết bị của phiếu đang sửa này
        $equipment = Equipment::where('status', 1)
            ->where('available', 1)
            ->where(function ($query) use ($equipmentUser) {
                $query->whereDoesntHave('users', function ($q) {
                    $q->where('equipment_users.status', EquipmentUser::STATUS_BORROWING);
                })
                    ->orWhere('id', $equipmentUser->equipment_id);
            })->orderBy('name')->get();

        return view('equipment_users.edit', compact('equipmentUser', 'users', 'equipment'));
    }

    public function update(UpdateEquipmentUserRequest $request, EquipmentUser $equipmentUser)
    {
        $equipmentUser->update($request->validated());

        return redirect()->route('equipment-users.index')
            ->with('success', 'Phiếu mượn đã được cập nhật thành công!');
    }

    public function approve(EquipmentUser $equipmentUser)
    {
        if ($equipmentUser->status !== EquipmentUser::STATUS_PENDING) {
            return back()->with('error', 'Phiếu mượn này không ở trạng thái chờ duyệt!');
        }

        // Kiểm tra thiết bị còn rảnh không
        $isBorrowed = EquipmentUser::where('equipment_id', $equipmentUser->equipment_id)
            ->where('status', EquipmentUser::STATUS_BORROWING)
            ->exists();

        if ($isBorrowed) {
            return back()->with('error', 'Thiết bị này hiện đang được người khác mượn!');
        }

        $equipmentUser->update([
            'status' => EquipmentUser::STATUS_BORROWING,
            'ngaymuon' => now(),
            'hantra' => $equipmentUser->hantra ?? now()->addDays(14),
        ]);

        // Thông báo cho người dùng
        $equipmentUser->user->notify(new BorrowRequestResponse($equipmentUser));

        return back()->with('success', 'Đã duyệt yêu cầu mượn thiết bị! Hạn trả: '.Carbon::parse($equipmentUser->hantra ?? now()->addDays(14))->format('d/m/Y'));
    }

    public function reject(EquipmentUser $equipmentUser)
    {
        if ($equipmentUser->status !== EquipmentUser::STATUS_PENDING) {
            return back()->with('error', 'Phiếu mượn này không ở trạng thái chờ duyệt!');
        }

        $equipmentUser->update([
            'status' => EquipmentUser::STATUS_REJECTED,
        ]);

        // Thông báo cho người dùng
        $equipmentUser->user->notify(new BorrowRequestResponse($equipmentUser));

        return back()->with('success', 'Đã từ chối yêu cầu mượn thiết bị!');
    }

    public function return(EquipmentUser $equipmentUser)
    {
        if ($equipmentUser->status !== EquipmentUser::STATUS_BORROWING) {
            return back()->with('error', 'Phiếu mượn này không ở trạng thái đang mượn!');
        }

        $equipmentUser->update([
            'status' => EquipmentUser::STATUS_RETURNED,
            'ngaytra' => now(),
        ]);

        // Thông báo cho người dùng
        $equipmentUser->user->notify(new BorrowRequestResponse($equipmentUser));

        return back()->with('success', 'Đã xác nhận trả thiết bị thành công!');
    }

    public function destroy(EquipmentUser $equipmentUser)
    {
        // Kiểm tra nếu phiếu mượn chưa trả thiết bị (status = 1)
        if ($equipmentUser->status == EquipmentUser::STATUS_BORROWING) {
            return back()->with('error', 'Không thể xóa phiếu mượn này vì thiết bị chưa được hoàn trả!');
        }

        $equipmentUser->delete();

        return redirect()->route('equipment-users.index')
            ->with('success', 'Phiếu mượn đã được xóa thành công!');
    }

    /**
     * Report: list of equipment borrowed by each user in a year.
     */
    public function report(Request $request)
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
                    'equipment' => $entries->first()->equipment,
                    'count' => $entries->count(),
                    'from' => $entries->min('ngaymuon'),
                    'to' => $entries->max('ngaymuon'),
                ];
            }
            $reportData[] = ['user' => $user, 'items' => $items];
        }

        return view('equipment_users.report', compact('reportData', 'year'));
    }
}
