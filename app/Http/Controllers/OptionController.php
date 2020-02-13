<?php

namespace App\Http\Controllers;

use App\Models\Option;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class OptionController extends Controller
{
    public function index() {
        if(!Auth::user()->isAdmin()) {
            return redirectBackWithNotification('error', 'You are not authorised!');
        }
        return view('admin.preferences');
    }

    public function save(Request $request) {
        if(!Auth::user()->isAdmin()) {
            return redirectBackWithNotification('error', 'You are not authorised!');
        }

        $validator = Validator::make($request->all(), [
            'name'      => ['required', 'string'],
            'address'   => ['nullable', 'string'],
            'phone'     => ['nullable', 'string']
        ]);
        if($validator->fails()) {
            return redirectBackWithValidationError($validator);
        }

        $name = $this->createOrUpdateOption('company_name', $request->post('name'));

        $address = $this->createOrUpdateOption('company_address', $request->post('address'));

        $phone = $this->createOrUpdateOption('company_phone', $request->post('phone'));

        $image = $this->createOrUpdateOption('background_image', $request->post('image'));

        return redirectBackWithNotification('success', 'Successfully Updated!');
    }

    /**
     * @param string $name
     * @param string|null $value
     * @return bool
     */
    protected function createOrUpdateOption(string $name, string $value = null) {
        $option = Option::where('option_name', '=', $name)->first();
        if(!$option) {
            $option = new Option();
            $option->option_name = $name;
        }
        $option->option_content = $value;

        return $option->save();
    }
}
