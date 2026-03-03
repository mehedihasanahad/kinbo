<?php

namespace App\Http\Controllers;

use App\Mail\ReturnApproved;
use App\Mail\ReturnRejected;
use App\Models\Order;
use App\Models\ReturnRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class ReturnController extends Controller
{
    public function show(Order $order)
    {
        abort_if($order->user_id !== auth()->id(), 403);
        abort_unless($order->isReturnable() || $order->returnRequest, 403);

        $order->load(['items.product.translations', 'items.variant', 'returnRequest']);

        return view('orders.return', compact('order'));
    }

    public function store(Request $request, Order $order)
    {
        abort_if($order->user_id !== auth()->id(), 403);
        abort_unless($order->isReturnable(), 403);

        if ($order->returnRequest) {
            return redirect()->route('orders.return', $order)
                ->with('error', __('front.return_already_submitted'));
        }

        $data = $request->validate([
            'reason' => ['required', 'string', 'min:20', 'max:1000'],
        ]);

        $order->returnRequest()->create([
            'user_id' => auth()->id(),
            'reason'  => $data['reason'],
            'status'  => ReturnRequest::STATUS_PENDING,
        ]);

        return redirect()->route('orders.return', $order)
            ->with('success', __('front.return_submitted'));
    }
}
