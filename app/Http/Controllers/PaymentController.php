<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Package;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use PayPalHttp\HttpException;
use PayPalCheckoutSdk\Orders\OrdersCreateRequest;
use PayPalCheckoutSdk\Orders\OrdersCaptureRequest;
use PayPalCheckoutSdk\Core\PayPalHttpClient;
use PayPalCheckoutSdk\Core\SandboxEnvironment;

class PaymentController extends Controller
{
    private $client;

    public function __construct()
    {
        $environment = new SandboxEnvironment(env('PAYPAL_CLIENT_ID'), env('PAYPAL_CLIENT_SECRET'));
        $this->client = new PayPalHttpClient($environment);
    }

    public function createPayment(Request $request)
    {
        $item_id = $request->item_id;
        $googleDriveLink = $request->google_drive_link;

        if (str_starts_with($item_id, 'service-')) {
            $serviceId = (int)str_replace('service-', '', $item_id);
            $itemModel = \App\Models\Service::find($serviceId);
            $itemType = 'service';
        } elseif (str_starts_with($item_id, 'package-')) {
            $packageId = (int)str_replace('package-', '', $item_id);
            $itemModel = \App\Models\Package::find($packageId);
            $itemType = 'package';
        } else {
            return redirect()->back()->withErrors(['error' => 'Invalid selection']);
        }

        if (!$itemModel || is_null($itemModel->price)) {
            return redirect()->back()->withErrors(['error' => 'Item not found or missing price']);
        }

        $price = number_format($itemModel->price, 2, '.', '');

        $paypalRequest = new OrdersCreateRequest();
        $paypalRequest->prefer('return=representation');
        $paypalRequest->body = [
            "intent" => "CAPTURE",
            "purchase_units" => [[
                "amount" => [
                    "currency_code" => "USD",
                    "value" => $price
                ],
                "description" => $itemModel->name
            ]],
            "application_context" => [
                "cancel_url" => route('payment.cancel'),
                "return_url" => route('payment.success')
            ]
        ];

        try {
            $response = $this->client->execute($paypalRequest);

            session([
                'item_type' => $itemType,
                'item_id' => $itemModel->id,
                'google_drive_link' => $googleDriveLink,
            ]);

            foreach ($response->result->links as $link) {
                if ($link->rel === 'approve') {
                    return redirect()->away($link->href);
                }
            }

            return redirect('/order')->withErrors(['error' => 'Approval URL not found']);
        } catch (HttpException $ex) {
            return redirect()->back()->withErrors(['error' => 'Payment creation failed']);
        }
    }

    public function paymentSuccess(Request $request)
    {
        $token = $request->query('token');

        $captureRequest = new OrdersCaptureRequest($token);

        try {
            $response = $this->client->execute($captureRequest);

            $itemType = session('item_type');
            $itemId = session('item_id');
            $googleDriveLink = session('google_drive_link');

            $orderData = [
                'user_id' => auth()->id(),
                'google_drive_link' => $googleDriveLink,
                'status' => 'pending',
            ];
            
            if ($itemType === 'service') {
                $orderData['service_id'] = $itemId;
            } elseif ($itemType === 'package') {
                $orderData['package_id'] = $itemId;
            }
            
            Order::create($orderData);

            session()->forget(['item_type', 'item_id', 'google_drive_link']);

            return redirect('/dashboard')->with('success', 'Order placed and payment successful!');
        } catch (HttpException $ex) {
            return redirect('/')->withErrors(['error' => 'Payment failed']);
        }
    }

    public function paymentCancel()
    {
        return redirect('/order')->with('error', 'Payment was cancelled.');
    }
}
