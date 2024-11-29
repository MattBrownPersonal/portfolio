<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Stripe\StripeClient as Stripe;

class Checkout extends Model
{
    use HasFactory;

    public static function createPaymentIntent($request)
    {
        try {
            $stripe = new Stripe(config('app.stripe_secret'));
            $paymentIntent = $stripe->paymentIntents->create([
                'amount' => $request->price * 100,
                'currency' => 'gbp',
            ]);

            $output = [
                'clientSecret' => $paymentIntent->client_secret,
            ];
            echo json_encode($output);
        } catch (CardErrorException $e) {
            return back()->withErrors('Error! '.$e->getMessage());
        }
    }
}
