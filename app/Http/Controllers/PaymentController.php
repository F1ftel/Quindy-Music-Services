<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Package;
use App\Models\Service;
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
        $environment = new SandboxEnvironment(
            config('services.paypal.client_id'), 
            config('services.paypal.client_secret')
        );
        $this->client = new PayPalHttpClient($environment);
    }

    public function createPayment(Request $request)
    {
        $itemIdString = $request->item_id;
        $googleDriveLink = $request->google_drive_link;

        [$itemType, $itemModel] = $this->resolveItem($itemIdString);

        if (!$itemModel || is_null($itemModel->price)) {
            return redirect()->back()->withErrors(['error' => 'Item not found, missing price, or invalid selection']);
        }

        $price = number_format($itemModel->price, 2, '.', '');
        $paypalRequest = $this->buildPayPalOrderRequest($price, $itemModel->name);

        try {
            $response = $this->client->execute($paypalRequest);

            session([
                'item_type' => $itemType,
                'item_id' => $itemModel->id,
                'google_drive_link' => $googleDriveLink,
            ]);

            $approveLink = collect($response->result->links)->firstWhere('rel', 'approve');

            return $approveLink 
                ? redirect()->away($approveLink->href)
                : redirect('/order')->withErrors(['error' => 'Approval URL not found']);

        } catch (HttpException $ex) {
            return redirect()->back()->withErrors(['error' => 'Payment creation failed']);
        }
    }

    public function paymentSuccess(Request $request)
    {
        $token = $request->query('token');
        $captureRequest = new OrdersCaptureRequest($token);

        try {
            $this->client->execute($captureRequest);

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

    private function resolveItem(?string $itemId): array
    {
        $result = [null, null];

        if ($itemId) {
            if (str_starts_with($itemId, 'service-')) {
                $id = (int)str_replace('service-', '', $itemId);
                $result = ['service', Service::find($id)];
            } elseif (str_starts_with($itemId, 'package-')) {
                $id = (int)str_replace('package-', '', $itemId);
                $result = ['package', Package::find($id)];
            }
        }

        return $result;
    }

    private function buildPayPalOrderRequest(string $price, string $itemName): OrdersCreateRequest
    {
        $paypalRequest = new OrdersCreateRequest();
        $paypalRequest->prefer('return=representation');
        $paypalRequest->body = [
            "intent" => "CAPTURE",
            "purchase_units" => [[
                "amount" => [
                    "currency_code" => "USD",
                    "value" => $price
                ],
                "description" => $itemName
            ]],
            "application_context" => [
                "cancel_url" => route('payment.cancel'),
                "return_url" => route('payment.success')
            ]
        ];

        return $paypalRequest;
    }
}
