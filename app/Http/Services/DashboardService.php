<?php


namespace App\Http\Services;

use App\Models\News;
use App\Models\Package;
use App\Models\Post;
use App\Models\User;
use App\Models\Event;
use App\Models\Alumni;
use App\Models\EventTicket;
use App\Models\Notice;
use App\Models\JobPost;
use App\Models\Transaction;
use App\Models\SubscriptionOrder;
use App\Models\UserPackage;
use App\Traits\ResponseTrait;
use App\Models\UserMembershipPlan;
use Illuminate\Support\Facades\View;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class DashboardService
{
    use ResponseTrait;

    public function getSuperAdminDashboardData(): array
    {
        $data['title'] = __('Dashboard');
        $data['activeDashboard'] = 'active';

        $data['totalOrganizations'] = User::query()
            ->whereNotNull('tenant_id')
            ->distinct()
            ->count('tenant_id');

        $data['businessUsers'] = User::query()
            ->where('role', USER_ROLE_ADMIN)
            ->count();

        $data['subscriptionPlans'] = Package::query()->count();
        $data['activeSubscriptions'] = UserPackage::query()
            ->where('status', ACTIVE)
            ->count();

        $data['paidRevenue'] = (float) SubscriptionOrder::query()
            ->where('payment_status', PAYMENT_STATUS_PAID)
            ->sum('total');

        $data['paidOrdersTotal'] = SubscriptionOrder::query()
            ->where('payment_status', PAYMENT_STATUS_PAID)
            ->count();

        $data['revenueThisMonth'] = (float) SubscriptionOrder::query()
            ->where('payment_status', PAYMENT_STATUS_PAID)
            ->whereYear('created_at', now()->year)
            ->whereMonth('created_at', now()->month)
            ->sum('total');

        $data['pendingOrders'] = SubscriptionOrder::query()
            ->where('payment_status', PAYMENT_STATUS_PENDING)
            ->count();

        $data['newBusinessUsersWeek'] = User::query()
            ->where('role', USER_ROLE_ADMIN)
            ->where('created_at', '>=', now()->subDays(7))
            ->count();

        $data['chartLabels'] = [];
        $data['chartSignups'] = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = now()->subDays($i)->toDateString();
            $data['chartLabels'][] = now()->subDays($i)->format('M d');
            $data['chartSignups'][] = User::query()
                ->where('role', USER_ROLE_ADMIN)
                ->whereDate('created_at', $date)
                ->count();
        }

        $revenueByPackage = SubscriptionOrder::query()
            ->where('payment_status', PAYMENT_STATUS_PAID)
            ->selectRaw('package_id, SUM(COALESCE(total,0)) as total_revenue, COUNT(*) as paid_orders')
            ->groupBy('package_id')
            ->get()
            ->keyBy('package_id');

        $activeUsersByPackage = UserPackage::query()
            ->where('status', ACTIVE)
            ->selectRaw('package_id, COUNT(DISTINCT user_id) as user_count')
            ->groupBy('package_id')
            ->pluck('user_count', 'package_id');

        $planRows = Package::query()
            ->orderBy('name')
            ->get()
            ->map(function (Package $package) use ($revenueByPackage, $activeUsersByPackage) {
                $row = $revenueByPackage->get($package->id);

                return (object) [
                    'id' => $package->id,
                    'name' => $package->name,
                    'max_questions' => $package->max_questions,
                    'max_teachers' => $package->max_teachers,
                    'max_question_sets' => $package->max_question_sets,
                    'max_classes' => $package->max_classes,
                    'active_users' => (int) ($activeUsersByPackage[$package->id] ?? 0),
                    'paid_orders' => $row ? (int) $row->paid_orders : 0,
                    'paid_revenue' => $row ? (float) $row->total_revenue : 0.0,
                ];
            });

        $data['planRows'] = $planRows;
        $data['planRevenueLabels'] = $planRows->pluck('name')->values()->all();
        $data['planRevenueValues'] = $planRows->pluck('paid_revenue')->map(fn ($value) => round((float) $value, 2))->values()->all();

        return $data;
    }
}
