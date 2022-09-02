<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Classroom;
use App\Models\Payment;
use Inertia\Inertia;

class DashboardController extends Controller
{
    public function index()
    {
        $classrooms = Classroom::withTrashed()->withCount('classroomUsers')->get();
        $activeClassrooms = $classrooms->whereNull('deleted_at');
        $lastMonthClassrooms = $classrooms->filter(function ($classroom) {
            return ($classroom->created_at < now()->startOfMonth() && (is_null($classroom->deleted_at) || $classroom->deleted_at >= now()->startOfMonth()->subMonth()));
        });

        $countActive = $activeClassrooms->count();
        $classroomsPanel = [
            'count' => $countActive,
            'lastMonth' => $lastMonthClassrooms->count() != 0 ? ($countActive - $lastMonthClassrooms->count()) / $lastMonthClassrooms->count() * 100 : $countActive,
        ];

        $countStudents = $activeClassrooms->sum('classroom_users_count');
        $studentsPanel = [
            'count' => $countStudents,
            'lastMonth' => $lastMonthClassrooms->sum('classroom_users_count') != 0 ? ($countStudents - $lastMonthClassrooms->sum('classroom_users_count')) / $lastMonthClassrooms->sum('classroom_users_count') * 100 : $countStudents,
        ];

        $subsAverage = $activeClassrooms->load('latestSubscription')->avg('latestSubscription.price_hr');
        $lastMonthSubs = $lastMonthClassrooms->load('latestSubscriptionLastMonth')->avg('latestSubscriptionLastMonth.price_hr');
        $subsPanel = [
            'average' => $subsAverage,
            'lastMonth' => $lastMonthSubs != 0 ? ($subsAverage - $lastMonthSubs) / $lastMonthSubs * 100 : $subsAverage, 2,
        ];

        $monthIncome = Payment::whereBetween('payment_date', [now()->startOfMonth(), now()->endOfMonth()])->sum('income');
        $lastMonthIncome = Payment::whereBetween('payment_date', [now()->startOfMonth()->subMonth(), now()->endOfMonth()->subMonth()])->sum('income');
        $incomePanel = [
            'income' => $monthIncome,
            'lastMonth' => $lastMonthIncome != 0 ? ($monthIncome - $lastMonthIncome) / $lastMonthIncome * 100 : $monthIncome,
        ];

        return Inertia::render('Dashboard/Index', compact('classroomsPanel', 'studentsPanel', 'subsPanel', 'incomePanel'));
    }
}
