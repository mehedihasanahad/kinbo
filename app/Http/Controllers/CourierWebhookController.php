<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\CourierOrder;
use App\Services\Couriers\SteadfastCourierService;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;

class CourierWebhookController extends Controller
{
    public function steadfast(Request $request): Response
    {
        $token = $request->bearerToken() ?? '';

        if (! app(SteadfastCourierService::class)->validateWebhookToken($token)) {
            Log::warning('Steadfast webhook: invalid bearer token.');
            return response('Unauthorized', 401);
        }

        $payload = $request->all();

        Log::info('Steadfast webhook received', $payload);

        $consignmentId = $payload['consignment_id'] ?? null;
        $status        = $payload['status'] ?? null;

        if ($consignmentId && $status) {
            CourierOrder::where('consignment_id', (string) $consignmentId)
                ->where('courier', 'steadfast')
                ->update([
                    'status'           => $status,
                    'response_payload' => $payload,
                ]);
        }

        return response('OK', 200);
    }
}
