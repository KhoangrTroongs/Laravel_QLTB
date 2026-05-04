<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreEquipmentRequest;
use App\Http\Requests\UpdateEquipmentRequest;
use App\Models\Category;
use App\Models\Equipment;
use App\Models\EquipmentUser;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;
use Intervention\Image\Encoders\WebpEncoder;
use Intervention\Image\Laravel\Facades\Image;

/**
 * Quản lý các hoạt động CRUD cho thiết bị.
 * Bao gồm việc liệt kê, tạo mới, cập nhật, xem chi tiết và xóa thiết bị.
 * Có tích hợp xử lý hình ảnh (WebP) và lọc/sắp xếp nâng cao.
 */
class EquipmentController extends Controller
{
    /**
     * Hiển thị danh sách thiết bị với khả năng tìm kiếm, lọc và sắp xếp.
     *
     * @return View
     */
    public function index(Request $request)
    {
        $query = Equipment::with('category')->withCount(['users as active_borrow_count' => function ($q) {
            $q->where('equipment_users.status', EquipmentUser::STATUS_BORROWING);
        }]);

        // Filter by status
        if ($request->filled('status') && $request->status !== '') {
            $query->where('status', $request->status);
        }

        // Search by name or model
        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('name', 'like', '%'.$request->search.'%')
                    ->orWhere('model', 'like', '%'.$request->search.'%');
            });
        }

        // Sort
        $sortField = $request->get('sort', 'id');
        $sortDir = $request->get('direction', 'asc');
        $allowed = ['id', 'name', 'model', 'status', 'availability'];
        if (! in_array($sortField, $allowed)) {
            $sortField = 'id';
        }

        if ($sortField === 'availability') {
            // Case when status=1 AND available=1 AND count borrowing=0
            $query->orderByRaw('(CASE WHEN status = 1 AND available = 1 AND active_borrow_count = 0 THEN 1 ELSE 0 END) '.($sortDir === 'desc' ? 'desc' : 'asc'));
        } else {
            $query->orderBy($sortField, $sortDir === 'desc' ? 'desc' : 'asc');
        }

        $equipment = $query->paginate(10)->withQueryString();

        return view('equipment.index', compact('equipment'));
    }

    /**
     * Hiển thị form tạo mới thiết bị.
     *
     * @return View
     */
    public function create()
    {
        $categories = Category::all();

        return view('equipment.create', compact('categories'));
    }

    /**
     * Lưu thiết bị mới vào cơ sở dữ liệu.
     * Xử lý tải lên hình ảnh, chuyển đổi sang định dạng WebP để tối ưu dung lượng.
     *
     * @return RedirectResponse
     */
    public function store(StoreEquipmentRequest $request)
    {
        $data = $request->validated();
        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $filename = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME).'_'.time().'.webp';
            $path = 'equipment/'.$filename;

            $image = Image::decode($file)
                ->scale(width: 800)
                ->encode(new WebpEncoder(quality: 80));

            Storage::disk('public')->put($path, (string) $image);
            $data['image'] = $path;
        }

        // Merge dynamic specs into spec column
        if ($request->has('specs')) {
            $data['spec'] = $request->specs;
        }

        Equipment::create($data);

        return redirect()->route('equipment.index')
            ->with('success', 'Thiết bị đã được thêm thành công!');
    }

    /**
     * Hiển thị thông tin chi tiết một thiết bị cụ thể.
     * Bao gồm lịch sử mượn trả của thiết bị đó.
     *
     * @return View
     */
    public function show(Equipment $equipment)
    {
        $equipment->load(['category', 'users' => function ($q) {
            $q->withTrashed()->orderBy('equipment_users.id', 'desc');
        }]);

        return view('equipment.show', compact('equipment'));
    }

    /**
     * Hiển thị form chỉnh sửa thông tin thiết bị.
     *
     * @return View
     */
    public function edit(Equipment $equipment)
    {
        $categories = Category::all();

        return view('equipment.edit', compact('equipment', 'categories'));
    }

    /**
     * Cập nhật thông tin thiết bị trong cơ sở dữ liệu.
     * Xử lý cập nhật hình ảnh (xóa ảnh cũ nếu có ảnh mới).
     *
     * @return RedirectResponse
     */
    public function update(UpdateEquipmentRequest $request, Equipment $equipment)
    {
        $data = $request->validated();
        if ($request->hasFile('image')) {
            if ($equipment->image) {
                Storage::disk('public')->delete($equipment->image);
            }

            $file = $request->file('image');
            $filename = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME).'_'.time().'.webp';
            $path = 'equipment/'.$filename;

            $image = Image::decode($file)
                ->scale(width: 800)
                ->encode(new WebpEncoder(quality: 80));

            Storage::disk('public')->put($path, (string) $image);
            $data['image'] = $path;
        }

        // Merge dynamic specs into spec column
        if ($request->has('specs')) {
            $data['spec'] = $request->specs;
        }

        $equipment->update($data);

        return redirect()->route('equipment.index')
            ->with('success', 'Thiết bị đã được cập nhật thành công!');
    }

    /**
     * Xóa thiết bị (Xóa mềm - Soft Delete).
     * Ràng buộc: Không thể xóa thiết bị đang trong trạng thái "Đang mượn".
     *
     * @return RedirectResponse
     */
    public function destroy(Equipment $equipment)
    {
        // Kiểm tra xem thiết bị có đang được mượn không
        $isBorrowed = $equipment->users()
            ->wherePivot('status', EquipmentUser::STATUS_BORROWING)
            ->exists();

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
