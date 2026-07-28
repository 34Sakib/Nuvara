<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Address;

class AddressController extends Controller
{
    public function index(Request $request)
    {
        $addresses = $request->user()->addresses()->orderBy('is_default', 'desc')->orderBy('created_at', 'desc')->get();
        return response()->json($addresses);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'label' => 'required|string|max:100',
            'fullName' => 'required|string|max:255',
            'address' => 'required|string|max:255',
            'city' => 'required|string|max:100',
            'state' => 'required|string|max:100',
            'zip' => 'required|string|max:20',
            'country' => 'required|string|max:100',
            'is_default' => 'nullable|boolean',
        ]);

        $user = $request->user();

        if (!empty($validated['is_default'])) {
            $user->addresses()->update(['is_default' => false]);
        }

        // If this is the user's first address, mark as default
        if ($user->addresses()->count() === 0) {
            $validated['is_default'] = true;
        }

        $address = $user->addresses()->create($validated);

        return response()->json([
            'message' => 'Address created successfully',
            'address' => $address,
            'addresses' => $user->addresses()->get()
        ], 201);
    }

    public function update(Request $request, $id)
    {
        $user = $request->user();
        $address = $user->addresses()->findOrFail($id);

        $validated = $request->validate([
            'label' => 'sometimes|required|string|max:100',
            'fullName' => 'sometimes|required|string|max:255',
            'address' => 'sometimes|required|string|max:255',
            'city' => 'sometimes|required|string|max:100',
            'state' => 'sometimes|required|string|max:100',
            'zip' => 'sometimes|required|string|max:20',
            'country' => 'sometimes|required|string|max:100',
            'is_default' => 'nullable|boolean',
        ]);

        if (!empty($validated['is_default'])) {
            $user->addresses()->where('id', '!=', $id)->update(['is_default' => false]);
        }

        $address->update($validated);

        return response()->json([
            'message' => 'Address updated successfully',
            'address' => $address,
            'addresses' => $user->addresses()->get()
        ]);
    }

    public function destroy(Request $request, $id)
    {
        $user = $request->user();
        $address = $user->addresses()->findOrFail($id);
        $address->delete();

        // If deleted address was default, set the latest remaining as default
        if ($address->is_default) {
            $firstRemaining = $user->addresses()->first();
            if ($firstRemaining) {
                $firstRemaining->update(['is_default' => true]);
            }
        }

        return response()->json([
            'message' => 'Address deleted successfully',
            'addresses' => $user->addresses()->get()
        ]);
    }
}
