<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Equipment;
use App\Models\EquipmentUser;
use App\Models\User;
use App\Notifications\NewBorrowRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Notification;
use Illuminate\View\View;

class HomeController extends Controller
{
    /**
     * Trang chủ: danh sách thiết bị còn có thể cho mượn.
     * Ai cũng có thể xem, nhưng chỉ user đã đăng nhập mới tạo được phiếu mượn.
     */
    public function index(Request $request): View
    {
        $query = Equipment::with('category')->where('status', 1)->where('available', 1);

        // Loại bỏ thiết bị đang được mượn (có phiếu mượn status=BORROWING)
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

        $availableEquipment = $query->latest()->paginate(12)->withQueryString();
        $categories = Category::all();

        return view('home.index', compact('availableEquipment', 'categories'));
    }

    /**
     * Trang chi tiết thiết bị.
     */
    public function show(Equipment $equipment): View
    {
        if ($equipment->available == 0) {
            abort(404);
        }
        $equipment->loadMissing(['users' => function ($q) {
            $q->where('equipment_users.status', EquipmentUser::STATUS_BORROWING);
        }]);

        $isAvailable = $equipment->status == 1
            && ! $equipment->users->where('pivot.status', EquipmentUser::STATUS_BORROWING)->count();

        return view('home.show', compact('equipment', 'isAvailable'));
    }

    /**
     * Form tạo phiếu mượn thiết bị (user đã đăng nhập).
     */
    public function createBorrow(Equipment $equipment): View|RedirectResponse
    {
        // Kiểm tra thiết bị có thể mượn không
        $isBorrowed = EquipmentUser::where('equipment_id', $equipment->id)
            ->where('status', EquipmentUser::STATUS_BORROWING)->exists();

        if ($equipment->status != 1 || $isBorrowed || $equipment->available == 0) {
            return redirect()->route('home')->with('error', 'Thiết bị này hiện không thể mượn.');
        }

        return view('home.borrow', compact('equipment'));
    }

    /**
     * Lưu phiếu mượn thiết bị.
     */
    public function storeBorrow(Request $request, Equipment $equipment): RedirectResponse
    {
        $validated = $request->validate([
            'description' => ['nullable', 'string', 'max:500'],
            'hantra' => ['nullable', 'date', 'after_or_equal:today'],
        ]);

        // Kiểm tra lại trước khi lưu
        $isBorrowed = EquipmentUser::where('equipment_id', $equipment->id)
            ->where('status', EquipmentUser::STATUS_BORROWING)->exists();

        if ($equipment->status != 1 || $isBorrowed) {
            return redirect()->route('home')->with('error', 'Thiết bị này hiện không thể mượn.');
        }

        // Kiểm tra user đang mượn thiết bị này chưa
        $alreadyBorrowing = EquipmentUser::where('user_id', Auth::id())
            ->where('equipment_id', $equipment->id)
            ->whereIn('status', [EquipmentUser::STATUS_PENDING, EquipmentUser::STATUS_BORROWING])
            ->exists();

        if ($alreadyBorrowing) {
            return back()->with('error', 'Bạn đã có một yêu cầu mượn hoặc đang mượn thiết bị này rồi!');
        }

        $borrowRecord = EquipmentUser::create([
            'user_id' => Auth::id(),
            'equipment_id' => $equipment->id,
            'ngaymuon' => now(),
            'hantra' => $request->hantra,
            'status' => EquipmentUser::STATUS_PENDING,
            'description' => $request->description,
        ]);

        // Thông báo cho tất cả Admin
        $admins = User::whereHas('roles', fn ($q) => $q->where('name', 'admin'))->get();
        Log::info('Sending NewBorrowRequest to '.$admins->count().' admins');
        Notification::send($admins, new NewBorrowRequest($borrowRecord));
        Log::info('NewBorrowRequest sent');

        return redirect()->route('profile.show')
            ->with('success', 'Yêu cầu mượn thiết bị của bạn đã được gửi. Vui lòng chờ quản trị viên phê duyệt!');
    }
}
