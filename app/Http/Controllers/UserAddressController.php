<?php

namespace App\Http\Controllers;

use App\Models\UserAddress;
use Illuminate\Http\Request;

class UserAddressController extends Controller
{
    public function index()
    {
        $addresses = auth()->user()->addresses()->latest()->get();
        return view('account.addresses.index', compact('addresses'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'label'          => 'nullable|string|max:50',
            'recipient_name' => 'required|string|max:191',
            'phone'          => 'required|string|max:20',
            'address_line'   => 'required|string|max:500',
            'city'           => 'required|string|max:100',
            'district'       => 'required|string|max:100',
            'upazila'        => 'nullable|string|max:100',
            'zip_code'       => 'nullable|string|max:10',
            'is_default'     => 'boolean',
        ]);

        $data['user_id'] = auth()->id();

        if (! empty($data['is_default'])) {
            auth()->user()->addresses()->update(['is_default' => false]);
        }

        // If this is the first address, make it default automatically
        if (auth()->user()->addresses()->count() === 0) {
            $data['is_default'] = true;
        }

        UserAddress::create($data);

        return back()->with('address_success', __('front.address_added'));
    }

    public function update(Request $request, UserAddress $address)
    {
        abort_if($address->user_id !== auth()->id(), 403);

        $data = $request->validate([
            'label'          => 'nullable|string|max:50',
            'recipient_name' => 'required|string|max:191',
            'phone'          => 'required|string|max:20',
            'address_line'   => 'required|string|max:500',
            'city'           => 'required|string|max:100',
            'district'       => 'required|string|max:100',
            'upazila'        => 'nullable|string|max:100',
            'zip_code'       => 'nullable|string|max:10',
            'is_default'     => 'boolean',
        ]);

        if (! empty($data['is_default'])) {
            auth()->user()->addresses()->update(['is_default' => false]);
        }

        $address->update($data);

        return back()->with('address_success', __('front.address_updated'));
    }

    public function destroy(UserAddress $address)
    {
        abort_if($address->user_id !== auth()->id(), 403);

        $wasDefault = $address->is_default;
        $address->delete();

        // Promote the most recent remaining address to default
        if ($wasDefault) {
            auth()->user()->addresses()->latest()->first()?->update(['is_default' => true]);
        }

        return back()->with('address_success', __('front.address_deleted'));
    }

    public function setDefault(UserAddress $address)
    {
        abort_if($address->user_id !== auth()->id(), 403);

        auth()->user()->addresses()->update(['is_default' => false]);
        $address->update(['is_default' => true]);

        return back()->with('address_success', __('front.address_set_default'));
    }
}
