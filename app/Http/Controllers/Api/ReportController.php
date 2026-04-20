<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\EquipmentUser;
use App\Models\Equipment;
use App\Models\User;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    /**
     * Get aggregate report data
     */
    public function index(Request $request)
    {
        $year = $request->input('year', date('Y'));
        $userId = $request->input('user_id');

        // Chi tiết báo cáo theo từng nhân viên (Khôi phục logic SSR)
        if ($userId) {
            $user = User::find($userId);
            if (!$user) return response()->json(['message' => 'Không tìm thấy nhân viên'], 44);

            $history = EquipmentUser::with('equipment')
                ->where('user_id', $userId)
                ->whereYear('ngaymuon', $year)
                ->orderBy('ngaymuon', 'desc')
                ->get();

            return response()->json([
                'is_user_report' => true,
                'user' => $user,
                'year' => $year,
                'history' => $history,
                'summary' => [
                    'total' => $history->count(),
                    'active' => $history->where('status', 2)->count(),
                    'returned' => $history->where('status', 3)->count()
                ]
            ]);
        }
        
        // Retrieve all users who have borrowing history
        $userStats = User::whereHas('borrowRecords')->withCount([
             'borrowRecords as total_borrows',
             'borrowRecords as active_borrows' => function ($query) {
                 $query->where('status', 2);
             },
             'borrowRecords as returned_borrows' => function ($query) {
                 $query->where('status', 3);
             },
             'borrowRecords as overdue_borrows' => function ($query) {
                 $query->whereNull('ngaytra')->where('hantra', '<', now());
             }
        ])->get();

        return response()->json([
            'year' => $year,
            'user_stats' => $userStats
        ]);
    }
}
