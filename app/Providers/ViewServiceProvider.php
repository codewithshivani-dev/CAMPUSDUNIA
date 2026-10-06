<?php

namespace App\Providers;

use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\ServiceProvider;
use App\Models\InstituteBasicDetails; // other table model

class ViewServiceProvider extends ServiceProvider
{
    public function boot()
    {
        View::composer('*', function ($view) {

            if (Auth::check()) {
                $user = Auth::user();

                // Fetch data from another table using user_id
                $serviceInstitutedetails = InstituteBasicDetails::where('fincap_merchant_id', $user->institute_id)
                ->with(['documents'])
                ->first();

                // Share with all views
                $view->with([
                    'serviceInstitutedetails' => $serviceInstitutedetails
                ]);
            }
        });
    }
}
