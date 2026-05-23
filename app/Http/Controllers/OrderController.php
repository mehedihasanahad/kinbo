<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Response;

class OrderController extends Controller
{
    public function index()
    {
        $orders = auth()->user()->orders()
            ->with(['items'])
            ->latest()
            ->paginate(15);

        return view('orders.index', compact('orders'));
    }

    public function show(Order $order)
    {
        abort_if($order->user_id !== auth()->id(), 403);

        $order->load(['items.product.translations', 'items.variant', 'manualPayment', 'statusHistory', 'shippingRate']);

        return view('orders.show', compact('order'));
    }

    public function invoice(Order $order): Response
    {
        abort_if($order->user_id !== auth()->id(), 403);

        $order->load(['items', 'manualPayment', 'coupon', 'shippingRate']);

        $output = Pdf::loadView('orders.invoice', compact('order'))
            ->setPaper('a4', 'portrait')
            ->output();

        return response($output)->header('Content-Type', 'application/pdf');
    }
}
