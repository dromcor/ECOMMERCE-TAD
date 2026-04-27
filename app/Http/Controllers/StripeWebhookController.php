<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Stripe\Webhook;
use Stripe\Stripe;
use Illuminate\Support\Facades\Mail;
use App\Mail\OrderConfirmed;

class StripeWebhookController extends Controller
{
    // Handle Stripe webhooks (only minimal handler for checkout.session.completed)
    public function handle(Request $request)
    {
        $payload = $request->getContent();
        $sigHeader = $request->header('stripe-signature');

        // Validate webhook signature if secret is set
        $webhookSecret = env('STRIPE_WEBHOOK_SECRET');
        try {
            if ($webhookSecret) {
                Stripe::setApiKey(config('services.stripe.secret'));
                $event = Webhook::constructEvent($payload, $sigHeader, $webhookSecret);
            } else {
                // Fallback (not secure) - parse payload directly
                $event = json_decode($payload, true);
            }
        } catch (\Exception $e) {
            Log::warning('Stripe webhook signature verification failed: '.$e->getMessage());
            return response()->json(['status' => 'invalid signature'], 400);
        }

        $type = is_array($event) ? ($event['type'] ?? null) : ($event->type ?? null);

        if ($type === 'checkout.session.completed') {
            $session = is_array($event) ? ($event['data']['object'] ?? null) : ($event->data->object ?? null);
            $clientRef = is_array($session) ? ($session['client_reference_id'] ?? null) : ($session->client_reference_id ?? null);
            if ($session && $clientRef) {
                $orderId = (int) $clientRef;

                DB::transaction(function () use ($orderId, $session) {
                    // Update payment record (insert or update)
                    DB::table('payments')->insert([
                        'pedido_id' => $orderId,
                        'metodo' => is_array($session) ? ($session['payment_method_types'][0] ?? 'stripe') : ($session->payment_method_types[0] ?? 'stripe'),
                        'amount_cents' => (int)(is_array($session) ? ($session['amount_total'] ?? 0) : ($session->amount_total ?? 0)),
                        'status' => 'succeeded',
                        'provider_ref' => is_array($session) ? ($session['id'] ?? null) : ($session->id ?? null),
                        'raw' => json_encode($session),
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);

                    DB::table('orders')->where('id', $orderId)->update([
                        'estado' => 'paid',
                        'status_id' => DB::table('order_statuses')->where('nombre', 'paid')->value('id'),
                        'updated_at' => now(),
                    ]);
                });

                // Send confirmation email
                try {
                    $order = \App\Models\Order::with('lines.product', 'user')->find($orderId);
                    if ($order && $order->user) {
                        Mail::to($order->user->email)->queue(new OrderConfirmed($order));
                    }
                } catch (\Exception $ex) {
                    Log::error('Failed to queue order confirmation email: '.$ex->getMessage());
                }

                Log::info('Stripe checkout completed for order '.$orderId);
            }
        }

        return response()->json(['received' => true]);
    }
}
