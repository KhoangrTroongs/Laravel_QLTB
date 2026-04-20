<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\EquipmentUserResource;
use App\Models\Equipment;
use App\Models\EquipmentUser;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    /**
     * Get dashboard statistics.
     */
    public function stats(): JsonResponse
    {
        $stats = [
            'total_equipment' => Equipment::count(),
            'borrowing_count' => EquipmentUser::where('status', EquipmentUser::STATUS_BORROWING)->count(),
            'pending_count' => EquipmentUser::where('status', EquipmentUser::STATUS_PENDING)->count(),
            'overdue_count' => EquipmentUser::where('status', EquipmentUser::STATUS_BORROWING)
                ->where('hantra', '<', now())
                ->count(),
            'total_users' => User::count(),
        ];

        // Recent activities
        $recentActivities = EquipmentUser::with(['user', 'equipment'])
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        // Top users
        $topUsers = User::withCount('equipments')
            ->orderBy('equipments_count', 'desc')
            ->limit(5)
            ->get();

        // Popular equipment
        $popularEquipment = Equipment::withCount('users')
            ->orderBy('users_count', 'desc')
            ->limit(5)
            ->get();

        // Trend data (last 7 days)
        $trend = EquipmentUser::select(DB::raw('DATE(ngaymuon) as date'), DB::raw('count(*) as count'))
            ->where('ngaymuon', '>=', now()->subDays(7))
            ->groupBy('date')
            ->orderBy('date', 'asc')
            ->get();

        return response()->json([
            'stats' => $stats,
            'recent' => EquipmentUserResource::collection($recentActivities),
            'top_users' => $topUsers->map(fn ($u) => ['name' => $u->name, 'count' => $u->equipments_count]),
            'popular' => $popularEquipment->map(fn ($e) => ['name' => $e->name, 'count' => $e->users_count]),
            'trend' => $trend,
        ]);
    }
}
