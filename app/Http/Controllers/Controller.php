<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
// use Illuminate\Contracts\Auth\Access\Gate;
use Illuminate\Support\Facades\Auth;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Support\Facades\Gate as FacadesGate;

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
        $user = Auth::user();
        if(!$user || !$user->is_active){
            throw new AuthorizationException("User is not active, please contact support.");
            // Auth::logout();
            // request()->session()->invalidate();
            // return redirect()->route('login');
        }
        if($user->is_super_admin){
            return true;
        }
        $baseName = (new \ReflectionClass($model))->getShortName();
        $abilityMap = $this->resourceAbilityMap()[$ability];
        $ability = $baseName.'-'.$abilityMap;
        // dd($ability, $user->isAbleTo($ability), $user->hasPermission($ability), $user->roles()->first()->permissions );
        if($user->isAbleTo($ability)){
            return true;
        };
        throw new AuthorizationException("User is Unauthorized");
        // throw new UnauthorizedException();
    }
}
