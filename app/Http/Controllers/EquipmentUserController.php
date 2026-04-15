<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreEquipmentUserRequest;
use App\Http\Requests\UpdateEquipmentUserRequest;
use App\Models\Equipment;
use App\Models\EquipmentUser;
use App\Models\User;
use Illuminate\Http\Request;

class EquipmentUserController extends Controller
{
    public function index(Request $request)
    {
        $query = EquipmentUser::with(['user', 'equipment']);

        // Filter by status
        if ($request->filled('status') && $request->status !== '') {
            $query->where('status', $request->status);
        }

        // Search by user name or equipment name
        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->whereHas('user', fn ($u) => $u->where('name', 'like', '%' . $request->search . '%'))
                  ->orWhereHas('equipment', fn ($e) => $e->where('name', 'like', '%' . $request->search . '%'));
            });
        }

        // Date range filter
        if ($request->filled('from_date')) {
            $query->whereDate('ngaymuon', '>=', $request->from_date);
        }
        if ($request->filled('to_date')) {
            $query->whereDate('ngaymuon', '<=', $request->to_date);
        }

        // Join để hỗ trợ sắp xếp theo tên NV và thiết bị
        $query->select('equipment_users.*')
            ->join('users', 'equipment_users.user_id', '=', 'users.id')
            ->join('equipment', 'equipment_users.equipment_id', '=', 'equipment.id');

        // Sort logic
        $sortField = $request->get('sort', 'id');
        $sortDir   = $request->get('direction', 'desc');
        
        $sortMapping = [
            'id'        => 'equipment_users.id',
            'employee'  => 'users.employee_id',
            'name'      => 'users.name',
            'equipment' => 'equipment.name',
            'date'      => 'equipment_users.ngaymuon',
            'status'    => 'equipment_users.status',
        ];

        $column = $sortMapping[$sortField] ?? 'equipment_users.id';
        $query->orderBy($column, $sortDir === 'asc' ? 'asc' : 'desc');

        $records = $query->paginate(10)->withQueryString();

        return view('equipment_users.index', compact('records'));
    }

    public function create()
    {
        $users     = User::where('status', 1)->orderBy('name')->get();
        
        // Chỉ lấy thiết bị đang hoạt động VÀ không có ai đang mượn (status = 1 trong bảng equipment_users)
        $equipment = Equipment::where('status', 1)
            ->whereDoesntHave('users', function ($q) {
                $q->where('equipment_users.status', 1);
            })->orderBy('name')->get();

        return view('equipment_users.create', compact('users', 'equipment'));
    }

    public function store(StoreEquipmentUserRequest $request)
    {
        EquipmentUser::create($request->validated());

        return redirect()->route('equipment-users.index')
            ->with('success', 'Phiếu mượn thiết bị đã được tạo thành công!');
    }

    public function show(EquipmentUser $equipmentUser)
    {
        $equipmentUser->load(['user', 'equipment']);

        return view('equipment_users.show', compact('equipmentUser'));
    }

    public function edit(EquipmentUser $equipmentUser)
    {
        $users     = User::where('status', 1)->orderBy('name')->get();
        
        // Lấy thiết bị rảnh HOẶC chính là thiết bị của phiếu đang sửa này
        $equipment = Equipment::where('status', 1)
            ->where(function ($query) use ($equipmentUser) {
                $query->whereDoesntHave('users', function ($q) {
                    $q->where('equipment_users.status', 1);
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

    public function destroy(EquipmentUser $equipmentUser)
    {
        $equipmentUser->delete();

        return redirect()->route('equipment-users.index')
            ->with('success', 'Phiếu mượn đã được xóa thành công!');
    }

    /**
     * Report: list of equipment borrowed by each user in a year.
     */
    public function report(Request $request)
    {
        $year  = $request->get('year', now()->year);
        $users = User::withCount(['equipments' => function ($q) use ($year) {
            $q->whereYear('equipment_users.ngaymuon', $year);
        }])->having('equipments_count', '>', 0)->get();

        $reportData = [];
        foreach ($users as $user) {
            $borrowed = EquipmentUser::with('equipment')
                ->where('user_id', $user->id)
                ->whereYear('ngaymuon', $year)
                ->get()
                ->groupBy('equipment_id');

            $items = [];
            foreach ($borrowed as $equipmentId => $entries) {
                $items[] = [
                    'equipment' => $entries->first()->equipment,
                    'count'     => $entries->count(),
                    'from'      => $entries->min('ngaymuon'),
                    'to'        => $entries->max('ngaymuon'),
                ];
            }
            $reportData[] = ['user' => $user, 'items' => $items];
        }

        return view('equipment_users.report', compact('reportData', 'year'));
    }
}
