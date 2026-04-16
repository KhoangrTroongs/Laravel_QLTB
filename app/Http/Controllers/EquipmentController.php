<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreEquipmentRequest;
use App\Http\Requests\UpdateEquipmentRequest;
use App\Models\Equipment;
use Illuminate\Http\Request;

class EquipmentController extends Controller
{
    public function index(Request $request)
    {
        $query = Equipment::query();

        // Filter by status
        if ($request->filled('status') && $request->status !== '') {
            $query->where('status', $request->status);
        }

        // Search by name or model
        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('model', 'like', '%' . $request->search . '%');
            });
        }

        // Sort
        $sortField = $request->get('sort', 'id');
        $sortDir   = $request->get('direction', 'asc');
        $allowed   = ['id', 'name', 'model', 'status'];
        if (! in_array($sortField, $allowed)) {
            $sortField = 'id';
        }
        $query->orderBy($sortField, $sortDir === 'desc' ? 'desc' : 'asc');

        $equipment = $query->paginate(10)->withQueryString();

        return view('equipment.index', compact('equipment'));
    }

    public function create()
    {
        return view('equipment.create');
    }

    public function store(StoreEquipmentRequest $request)
    {
        $data = $request->validated();
        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('equipment', 'public');
        }
        Equipment::create($data);

        return redirect()->route('equipment.index')
            ->with('success', 'Thiết bị đã được thêm thành công!');
    }

    public function show(Equipment $equipment)
    {
        return view('equipment.show', compact('equipment'));
    }

    public function edit(Equipment $equipment)
    {
        return view('equipment.edit', compact('equipment'));
    }

    public function update(UpdateEquipmentRequest $request, Equipment $equipment)
    {
        $data = $request->validated();
        if ($request->hasFile('image')) {
            if ($equipment->image) {
                \Illuminate\Support\Facades\Storage::disk('public')->delete($equipment->image);
            }
            $data['image'] = $request->file('image')->store('equipment', 'public');
        }
        $equipment->update($data);

        return redirect()->route('equipment.index')
            ->with('success', 'Thiết bị đã được cập nhật thành công!');
    }

    public function destroy(Equipment $equipment)
    {
        // Kiểm tra xem thiết bị có đang được mượn không
        $isBorrowed = $equipment->users()->wherePivot('status', 1)->exists();

        if ($isBorrowed) {
            return back()->with('error', 'Không thể xóa thiết bị này vì đang có nhân viên mượn và chưa trả!');
        }

        // Thực hiện xoá mềm
        $equipment->update(['available' => 0]);
        $equipment->delete();

        return redirect()->route('equipment.index')
            ->with('success', 'Thiết bị đã được đưa vào thùng rác thành công!');
    }
}
