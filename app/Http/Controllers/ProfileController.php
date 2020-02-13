<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class ProfileController extends Controller
{
    public function index() {
        return view('admin.profile.index');
    }

    public function update(Request $request) {
        $validator = Validator::make($request->all(), [
            'mobile'    => ['required', 'numeric'],
            'name'      => ['required', 'string', 'max:255'],
            'address'   => ['nullable', 'string', 'min:8', 'max:255'],
            'image'     => ['nullable', 'string'],
        ]);
        if ($validator->fails()) {
            return redirectBackWithValidationError($validator);
        }

        $user = User::findOrFail(Auth::id());

        if($request->has('email') && $user->email !== $request->post('email')) {
            $validator = Validator::make($request->all(), [
                'email'     => ['string', 'email', 'max:255', 'unique:bsoft_users'],
            ]);
            if ($validator->fails()) {
                return redirectBackWithValidationError($validator);
            }
        }

        $user->name = $request->post('name');
        $user->email = $request->post('email');
        $user->mobile = $request->post('mobile');
        $user->address = $request->post('address');
        $user->image = $request->post('image');

        if(!$user->save()) {
            return redirectBackWithNotification();
        }
        addActivity('user', $user->id, 'Profile Updated');

        return redirectBackWithNotification('success', 'Profile Successfully Updated!');
    }

    public function changePassword(Request $request) {
        $user = User::findOrFail(Auth::id());

        $validator = Validator::make($request->all(), [
            'old_password' => [
                'required', 'min:8', function ($attribute, $value, $fail) {
                    if (!Hash::check($value, Auth::user()->password)) {
                        $fail('Old Password didn\'t match');
                    }
                },
            ],
            'password'  => ['required', 'string', 'confirmed', 'min:8', function ($attribute, $value, $fail) {
                if (Hash::check($value, Auth::user()->password)) {
                    $fail('Old Password & New Password is same!');
                }
            }]
        ]);
        if ($validator->fails()) {
            return redirectBackWithValidationError($validator);
        }

        $user->password = Hash::make($request->post('password'));

        if(!$user->save()) {
            return redirectBackWithNotification();
        }
        addActivity('user', $user->id, 'Password Changed.');

        return redirectBackWithNotification('success', 'Password Successfully Updated!');
    }
}
