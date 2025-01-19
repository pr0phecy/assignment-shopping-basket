<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use App\Models\UserContactDetail;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\RedirectResponse;
use Illuminate\Contracts\View\Factory;

class ProfileController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the profile.
     */
    public function index(): Factory|View
    {
        /** @var User $user */
        $user = Auth::user();

        return view('profile.index', compact('user'));
    }

    /**
     * Show the edit profile form.
     */
    public function edit(): Factory|View
    {
        /** @var User $user */
        $user = Auth::user();

        return view('profile.edit', compact('user'));
    }

    /**
     * Handle the profile update.
     *
     * @property string      $name
     * @property string      $email
     * @property string|null $phone
     * @property string|null $address_line_1
     * @property string|null $city
     * @property string|null $postal_code
     * @property string|null $country
     */
    public function update(Request $request): RedirectResponse
    {
        /** @var User $user */
        $user = Auth::user();

        $user->update([
            'name' => $request->name,
        ]);

        UserContactDetail::updateOrCreate(
            ['user_id' => $user->id],
            [
                'phone'          => $request->phone,
                'address_line_1' => $request->address_line_1,
                'city'           => $request->city,
                'postal_code'    => $request->postal_code,
                'country'        => $request->country,
            ]
        );

        return redirect()->route('profile.index')->with('success', 'Profile updated successfully!');
    }
}
