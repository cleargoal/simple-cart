<?php

namespace App\Jobs;

use App\Mail\LowStockNotification as LowStockNotificationMail;
use App\Models\Product;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class LowStockNotification implements ShouldQueue
{
    use Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct(
        public Product $product
    ) {
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        // Send to all configured admin emails
        $adminEmails = config('mail.admin_emails');

        foreach ($adminEmails as $email) {
            Mail::to($email)->send(new LowStockNotificationMail($this->product));
        }
    }
}
