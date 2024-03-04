<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

/**
 * References:
 * @see User
 * @see EmployeeType
 * 
 */
class Employee extends Model
{
    use HasFactory;

    /**
       This employee belongs to one user instance
       One employee to one user
       * @see User::employee()
    */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
        This employee belongs to one employee type
        Many employees to one employeeType
       * @see EmployeeType::employees()
    */
    public function employeeType()
    {
        return $this->belongsTo(EmployeeType::class);
    }

    /**
       This employee has many purchase orders
       One to many
       * @see PurchaseOrder::employee()
    */
    public function purchaseOrders()
    {
        return $this->hasMany(PurchaseOrder::class);
    }

    /**
       This employee has many sales orders
       One to Many
       * @see SalesOrder::employee()
    */
    public function salesOrders()
    {
        return $this->hasMany(SalesOrder::class);
    }


    /**
       This employee has many logs
       One to Many
       * @see Log::employee()
    */
    public function logs()
    {
        return $this->hasMany(Log::class);
    }

    // load all relations
    public function loadAllRelations()
    {
        return $this->load('user', 'employeeType', 'purchaseOrders', 'salesOrders', 'user.logs');
    }

    // clock in or out
    public function clockIn()
    {
        $clockInAction = Action::where('action', 'clock in')->first()->id;
        $clockOutAction = Action::where('action', 'clock out')->first()->id;


        // Get the last action of the user that is either clock in or clock out
        $lastAction = Log::where('user_id', $this->user->id)
            ->whereIn('action_id', [$clockInAction, $clockOutAction])
            ->latest()
            ->first();

        // If the last action was a clock in make a clock out log
        if ($lastAction && $lastAction->action->action === 'clock in') {
            $now = Carbon::now();
            $timeWorked = $lastAction ? $now->diff($lastAction->created_at)->format('%H:%I:%S') : '00:00:00';
            Log::create([
                'user_id' => $this->user->id,
                'action_id' => $clockOutAction,
                'description' => $timeWorked,
            ]);
        }
        // If the last action was a clock out make a clock in log
        elseif (!$lastAction || $lastAction->action->action === 'clock out') {
            Log::create([
                'user_id' => $this->user->id,
                'action_id' => $clockInAction,
            ]);

        }

        return $this->user->logs()->latest()->first();
    }


    // get weekly hours
    public function getWeeklyHoursAttribute()
    {
        $clockInId = Action::where('action', 'clock in')->first()->id;
        $clockOutId = Action::where('action', 'clock out')->first()->id;

        // Get the start and end of the week
        // Monday 12:00 AM
        $startOfWeek = Carbon::now()->startOfWeek();
        // Sunday 11:59:59 PM
        $endOfWeek = Carbon::now()->endOfWeek()->endOfDay();
        // Filter the logs for the week
        $logs = $this->user->logs()->whereIn('action_id', [$clockInId, $clockOutId])->whereBetween('created_at', [$startOfWeek, $endOfWeek])->get();

        $weeklyHours = 0;
        $clockInLog = null;

        foreach ($logs as $log) {
            if ($log->action_id == $clockInId) {
                $clockInLog = $log;
            } elseif ($log->action_id == $clockOutId && $clockInLog) {
                $weeklyHours += $log->created_at->diffInMinutes($clockInLog->created_at) / 60;
                $clockInLog = null;
            }
        }

        return $weeklyHours;


    }

    // get weekly pay
    public function getWeeklyPayAttribute()
    {
        $weeklyPay = $this->getWeeklyHoursAttribute() * $this->hourly_rate;
        return round($weeklyPay, 2);
    }
    protected $fillable = [
        'user_id',
        'employee_type_id',
        'role',
    ];
}
