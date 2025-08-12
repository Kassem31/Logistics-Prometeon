<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Contracts\Auth\Access\Gate;
use Illuminate\Support\Facades\Auth;
use Illuminate\Auth\Access\AuthorizationException;
class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    protected function resourceAbilityMap()
    {
        return [
            'index' => 'list',
            'show' => 'view',
            'create' => 'create',
            'store' => 'create',
            'edit' => 'edit',
            'update' => 'edit',
            'destroy' => 'delete',
        ];
    }
    public function authorize($ability, $model)
    {
        if(!Auth::user()->is_active){
            Auth::logout();
            request()->session()->invalidate();
            return redirect()->route('login');
        }
        if(Auth::user()->is_super_admin){
            return true;
        }
        $baseName = (new \ReflectionClass($model))->getShortName();
        $abilityMap = $this->resourceAbilityMap()[$ability];
        $ability = $baseName.'-'.$abilityMap;
        if(Auth::user()->can($ability)){
            return true;
        };
        throw new AuthorizationException("User is Unauthorized");
        // throw new UnauthorizedException();
    }
}
