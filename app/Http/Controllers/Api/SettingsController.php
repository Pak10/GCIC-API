<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Administration\Setting;
use App\Http\Resources\Administration\SettingsResource;
use App\Http\Requests\Api\Settings\UpdateSettingsRequest;
use Auth;
use Log;

class SettingsController extends Controller
{
    
    public function getSettings(Request $request)
    {
        $user  = Auth::user();

        if(!($user->can('update-settings'))){

            return response()->json([

                'message' => 'User does not have access to this resource'
            ],403);
        }

        $setting = Setting::orderBy('created_at', 'asc')->first();

        return new SettingsResource($setting);

    }


    public function updateSettings(UpdateSettingsRequest $request)
    {
        $user  = Auth::user();

        $validated = $request->validated();

        $setting = Setting::orderBy('created_at', 'asc')->first();

        if($setting === null){

            return response()->json([

                'message' => 'Invalid setting'
            ]);
        }

        $setting->update($validated);

        return new SettingsResource($setting);

    }
}
