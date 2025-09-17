<?php

namespace App\Http\Controllers\Website;

use App\Models\Admin;
use App\Models\Order;
use App\Models\Transaction;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Services\Website\OrderService;
use Illuminate\Support\Facades\Session;
use App\Http\Requests\OrderShippingRequest;
use App\Services\Website\MyFatoorahService;
use App\Notifications\CreateOrderNotification;

class CheckoutController extends Controller
{
    protected $orderService, $myFatoorahService;
    public function __construct(OrderService $orderService, MyFatoorahService $myFatoorahService)
    {
        $this->orderService = $orderService;
        $this->myFatoorahService = $myFatoorahService;
    }

    public function showCheckoutPage()
    {
        return view('website.checkout');
    }
    public function checkout(OrderShippingRequest  $request)
    {
        $shipping = $request->validated();

        // get invoice value from cart
        $invoiceValue = $this->orderService->getInvoiceValue($shipping);
        if ($invoiceValue < 1 || $invoiceValue == null) {
            return redirect()->back()->with('error', 'Cart is empty');
        }


        $data = [
            'CustomerName' => $shipping['first_name'] . ' ' . $shipping['last_name'],
            'NotificationOption' => 'LNK',
            'InvoiceValue' => $invoiceValue,
            'DisplayCurrencyIso' => 'EGP',
            'CustomerEmail' => $shipping['user_email'],
            'CallBackUrl' => 'http://localhost:8000/checkout/callback',
            'ErrorUrl' => 'http://localhost:8000/checkout/error',
            'Language' => app()->getLocale() == 'ar' ? 'ar' : 'en',
        ];
        $data = $this->myFatoorahService->checkout($data);

        // return $data;
        if ($url = $data["Data"]["InvoiceURL"]) {
            // store order
            $createOrder = $this->orderService->createOrder($shipping);
            if (!$createOrder) {
                Session::flash('error', 'Something went wrong');
                return redirect()->route('website.checkout.get');
            }
            // store transaction
            $createTransaction = $this->orderService->createTransaction($data, $createOrder->id);
            if (!$createTransaction) {
                Session::flash('error', 'Something went wrong');
                return redirect()->route('website.checkout.get');
            }
            return redirect($url);
        } else {
            Session::flash('error', 'Something went wrong');
            return redirect()->route('website.checkout.get');
        }
    }

    public function callback(Request $request)
    {
        $data = [];
        $data['key'] = $request->paymentId;
        $data['KeyType'] = 'paymentId';

        $response = $this->myFatoorahService->getPaymentStatus($data);
        return $response;

        // Change Order Status
        $user_id = Transaction::where('transaction_id', $response['Data']['InvoiceId'])->pluck('user_id');
        $order_id = Transaction::where('transaction_id', $response['Data']['InvoiceId'])->pluck('order_id');

        if ($response['Data']['InvoiceStatus'] == 'Paid') {
            Order::where('id', $order_id)->where('user_id', $user_id)->update(['status' => 'paid']);
            // $this->orderService->clearUserCart(auth('web')->user()->cart);


            // send notification to admin
            $order = Order::where('id', $order_id)->where('user_id', $user_id)->first();
            $admins = Admin::all();
            foreach ($admins as $admin) {
                $admin->notify(new CreateOrderNotification($order));
            }

            Session::flash('success', 'تم الدفع بنجاح  راقب حاله الاوردر !');
            return redirect()->route('website.checkout.get');
        }
        Session::flash('errro', 'فشلت عمليه الدفع حاول مره اخرى !!');
        return redirect()->route('website.checkout.get');
    }
    public function error()
    {
        // task set order status canceld
        Session::flash('error', 'Payment Failed try again Latter!');
        return redirect()->route('website.checkout.get');
    }
}
