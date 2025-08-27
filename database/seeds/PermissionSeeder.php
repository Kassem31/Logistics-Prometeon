<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use App\Models\Role;
use App\Models\ShippingBasic;
use ReflectionClass;



class PermissionSeeder extends Seeder
{

    protected $actions;
    protected $permissions = [];
    public function __construct()
    {
        $this->actions = collect([
            'list' => 'List',
            'create' => 'Create',
            'edit' => 'Edit'
        ]);
        $this->initPermissions();
    }
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('permissions')->delete();
        if (env('DB_CONNECTION') == 'sqlsrv') {
            DB::unprepared("DBCC CHECKIDENT ('permissions', RESEED, 0) ");
        } else {
            DB::unprepared("ALTER TABLE permissions AUTO_INCREMENT = 1; ");
        }
        // Ensure permission names are unique to avoid duplicate key errors
        $uniquePermissions = collect($this->permissions)->unique('name')->values()->all();
        DB::table('permissions')->insert($uniquePermissions);
    }

    protected function initPermissions()
    {

        $this->getModelNames()
            ->filter(function ($item) {
                return $this->hasPermission($item);
            })->each(function ($item) {

                $modelName = $this->getDisplayName($item);

                $className = $this->getShortClassName($item);
                $this->actions->each(function ($action, $index) use ($modelName, $className) {
                    $this->permissions[] =  [
                        'name' => $className . '-' . $index,
                        'display_name' => $action . ' ' . $modelName,
                        'description' => $modelName
                    ];
                });
            });
    }

    protected function getModelNames()
    {
        $modelClasses = $this->loadModelClasses();
        return  collect([
            User::class,
            Role::class,
            ShippingBasic::class
        ])->merge($modelClasses);
    }

    protected function getDisplayName($class)
    {
        $reflectionClass = new ReflectionClass($class);
        $instance = new $class;
        return $reflectionClass->getProperty('display_name')->getValue($instance);
    }

    protected function hasPermission($class)
    {
        $reflectionClass = new ReflectionClass($class);
        $instance = new $class;
        return !$reflectionClass->hasProperty('has_permission') ||
            ($reflectionClass->hasProperty('has_permission') && $reflectionClass->getProperty('has_permission')->getValue($instance));
    }

    protected function getShortClassName($class)
    {
        return (new ReflectionClass($class))->getShortName();
    }

    protected function loadModelClasses()
    {
        $classes = [];
        foreach (glob('app/Models/*.php') as $file) {
            $class = basename($file, '.php');
            $class = 'App\\Models\\' . $class;
            $classes[] = $class;
        }
        return $classes;
    }
}
