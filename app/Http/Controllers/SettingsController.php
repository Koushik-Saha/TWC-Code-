<?php

namespace App\Http\Controllers;

use App\Models\Settings;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class SettingsController extends Controller
{
    public function showSettingsForm()
    {
        return view('admin.settings.settings-layout');
    }
    public function processSettingsForm(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'header_title'              => ['required', 'string', 'max:255'],
        ]);

        if ($validator->fails()) {
            return redirectBackWithValidationError($validator);
        }


        $settingImg = $request->file('header_img');
        $rename = date('YmdHis') . '-' . time() . '-' . $settingImg->getClientOriginalName();
        $dir = './images/settings/logo/';
        $settingImg->move($dir,$rename);
        $settingImg = $dir.$rename;

        $settingImgIcon = $request->file('icon_img');
        $rename = date('YmdHis') . '-' . time() . '-' . $settingImgIcon->getClientOriginalName();
        $dir = './images/settings/icon/';
        $settingImgIcon->move($dir,$rename);
        $settingImgIcon = $dir.$rename;

        $settings = new Settings();

        $settings->header_title = $request->post('header_title');
        $settings->header_img = $settingImg;
        $settings->icon_img = $settingImgIcon;

        $settings->save();

//        return redirect()->back()->with('message','Setting updated successfully');

        return redirectBackWithNotification('success', 'Setting updated successfully!');
    }
}
