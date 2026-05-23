<?php

namespace App\Http\Controllers;

use App\Mail\SubscribeConfirmationMail;
use App\Models\Subscriber;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class SubscriberController extends Controller
{
    public function subscribe(Request $request)
    {
        $request->validate([
            'email' => 'required|email|max:150',
        ]);

        $email  = strtolower(trim($request->email));
        $locale = app()->getLocale();

        $existing = Subscriber::where('email', $email)->first();

        if ($existing) {
            if ($existing->status === 'active') {
                return $this->respond($request, 'already_subscribed');
            }
            // pending or unsubscribed — reset and resend
            $existing->update(['status' => 'pending', 'locale' => $locale]);
            Mail::to($email)->queue(new SubscribeConfirmationMail($existing));
            return $this->respond($request, 'check_email');
        }

        $subscriber = Subscriber::create([
            'email'  => $email,
            'locale' => $locale,
            'status' => 'pending',
        ]);

        Mail::to($email)->queue(new SubscribeConfirmationMail($subscriber));

        return $this->respond($request, 'check_email');
    }

    public function confirm(string $token)
    {
        $subscriber = Subscriber::where('token', $token)
            ->whereIn('status', ['pending', 'active'])
            ->firstOrFail();

        $subscriber->confirm();

        return view('pages.subscribe-result', [
            'type'    => 'confirmed',
            'message' => __('front.subscribe_confirmed'),
        ]);
    }

    public function unsubscribe(string $token)
    {
        $subscriber = Subscriber::where('token', $token)->firstOrFail();
        $subscriber->unsubscribe();

        return view('pages.subscribe-result', [
            'type'    => 'unsubscribed',
            'message' => __('front.subscribe_unsubscribed'),
        ]);
    }

    private function respond(Request $request, string $status)
    {
        if ($request->expectsJson() || $request->wantsJson()) {
            return response()->json(['status' => $status]);
        }
        return back()->with('subscribe_status', $status);
    }
}
