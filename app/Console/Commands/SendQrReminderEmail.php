<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;
use App\Models\Attendance;
use App\Models\User;
use App\Models\EmailLog;
use Carbon\Carbon;
use App\Mail\QrReminderEmail;

class SendQrReminderEmail extends Command
{
    protected $signature = 'email:qr-reminder';
    protected $description = 'Send an email reminder to users who have not scanned the QR code before 9 AM.';

    public function handle()
    {
        $today = Carbon::today();

        // Get all users who have not scanned the QR code before 9 AM
        $users = User::whereDoesntHave('attendances', function ($query) {
            $query->whereDate('created_at', Carbon::today())
                  ->whereTime('created_at', '<', '09:00:00');
        })->get();

        // Send email to each user
        foreach ($users as $user) {
            // Check if an email has already been sent today
            $emailLog = EmailLog::where('user_id', $user->id)
                                ->whereDate('date', $today)
                                ->first();

            if (!$emailLog) {
                Mail::to($user->email)->send(new QrReminderEmail($user));

                // Log the email notification
                EmailLog::create([
                    'user_id' => $user->id,
                    'date' => $today,
                ]);
            }
        }

        $this->info('Reminder emails have been sent successfully.');
    }
}