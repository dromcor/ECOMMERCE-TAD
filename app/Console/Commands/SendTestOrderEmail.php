<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class SendTestOrderEmail extends Command
{
    protected $signature = 'test:send-order-email';
    protected $description = 'Send a test order confirmation email for the first order (local only)';

    public function handle()
    {
        if (! app()->environment('local')) {
            $this->error('This command is only allowed in local environment.');
            return 1;
        }

        $order = \App\Models\Order::with('lines.product', 'user')->first();
        if (! $order) {
            $this->info('No orders found.');
            return 0;
        }

        \Mail::to($order->user->email ?? 'test@example.com')->queue(new \App\Mail\OrderConfirmed($order));
        $this->info('Email queued for order #'.$order->id);
        return 0;
    }
}
