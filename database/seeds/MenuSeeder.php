<?php
namespace Database\Seeders;

namespace Database\Seeders;

use App\Models\Bank;
use App\Models\Broker;
use App\Models\ContainerLoadType;
use App\Models\ContainerSize;
use App\Models\Country;
use App\Models\CustomSystem;
use App\Models\Inbound;
use App\Models\IncoForwarder;
use App\Models\IncoTerm;
use App\Models\InsuranceCompany;
use App\Models\MaterialGroup;
use App\Models\POHeader;
use App\Models\Port;
use App\Models\RawMaterial;
use App\Models\ShippingClearance;
use App\Models\ShippingLine;
use App\Models\ShippingUnit;
use App\Models\Supplier;
use App\Models\Permission;
use App\Models\Role;
use App\Models\ShippingBasic;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use ReflectionClass;
use Illuminate\Database\Seeder;


class MenuSeeder extends Seeder
{
    protected $menus;
    protected $permissions;

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Clear existing menus
        DB::table('menus')->delete();
        if (env('DB_CONNECTION') === 'sqlsrv') {
            DB::unprepared("DBCC CHECKIDENT ('menus', RESEED, 0)");
        } else {
            DB::unprepared('ALTER TABLE menus AUTO_INCREMENT = 1');
        }
        $this->permissions = Permission::get();
        $this->initMenus();
        DB::table('menus')->insert($this->menus);
    }

    /**
     * Initialize menu structure
     */
    protected function initMenus()
    {
        $this->menus = [
            [
                'level_1'       => 'Master Data',
                'l1_order'      => 1,
                'level_2'       => $this->getDisplayName(Bank::class),
                'l2_route'      => 'banks.index',
                'permission_id' => $this->getPermissionId(Bank::class),
            ],
            [
                'level_1'       => 'Master Data',
                'l1_order'      => 1,
                'level_2'       => $this->getDisplayName(Broker::class),
                'l2_route'      => 'brokers.index',
                'permission_id' => $this->getPermissionId(Broker::class),
            ],
            [
                'level_1'       => 'Master Data',
                'l1_order'      => 1,
                'level_2'       => $this->getDisplayName(Country::class),
                'l2_route'      => 'countries.index',
                'permission_id' => $this->getPermissionId(Country::class),
            ],
            [
                'level_1'       => 'Master Data',
                'l1_order'      => 1,
                'level_2'       => $this->getDisplayName(ContainerSize::class),
                'l2_route'      => 'container-sizes.index',
                'permission_id' => $this->getPermissionId(ContainerSize::class),
            ],
            [
                'level_1'       => 'Master Data',
                'l1_order'      => 1,
                'level_2'       => $this->getDisplayName(ContainerLoadType::class),
                'l2_route'      => 'load-types.index',
                'permission_id' => $this->getPermissionId(ContainerLoadType::class),
            ],
            [
                'level_1'       => 'Master Data',
                'l1_order'      => 1,
                'level_2'       => $this->getDisplayName(CustomSystem::class),
                'l2_route'      => 'custom-systems.index',
                'permission_id' => $this->getPermissionId(CustomSystem::class),
            ],
            [
                'level_1'       => 'Master Data',
                'l1_order'      => 1,
                'level_2'       => $this->getDisplayName(IncoForwarder::class),
                'l2_route'      => 'inco-forwarders.index',
                'permission_id' => $this->getPermissionId(IncoForwarder::class),
            ],
            [
                'level_1'       => 'Master Data',
                'l1_order'      => 1,
                'level_2'       => $this->getDisplayName(IncoTerm::class),
                'l2_route'      => 'inco-terms.index',
                'permission_id' => $this->getPermissionId(IncoTerm::class),
            ],
            [
                'level_1'       => 'Master Data',
                'l1_order'      => 1,
                'level_2'       => $this->getDisplayName(InsuranceCompany::class),
                'l2_route'      => 'insurance-companies.index',
                'permission_id' => $this->getPermissionId(InsuranceCompany::class),
            ],
            [
                'level_1'       => 'Master Data',
                'l1_order'      => 1,
                'level_2'       => $this->getDisplayName(MaterialGroup::class),
                'l2_route'      => 'material-groups.index',
                'permission_id' => $this->getPermissionId(MaterialGroup::class),
            ],
            [
                'level_1'       => 'Master Data',
                'l1_order'      => 1,
                'level_2'       => $this->getDisplayName(Port::class),
                'l2_route'      => 'ports.index',
                'permission_id' => $this->getPermissionId(Port::class),
            ],
            [
                'level_1'       => 'Master Data',
                'l1_order'      => 1,
                'level_2'       => $this->getDisplayName(RawMaterial::class),
                'l2_route'      => 'raw-materials.index',
                'permission_id' => $this->getPermissionId(RawMaterial::class),
            ],
            [
                'level_1'       => 'Master Data',
                'l1_order'      => 1,
                'level_2'       => $this->getDisplayName(ShippingLine::class),
                'l2_route'      => 'shipping-line.index',
                'permission_id' => $this->getPermissionId(ShippingLine::class),
            ],
            [
                'level_1'       => 'Master Data',
                'l1_order'      => 1,
                'level_2'       => $this->getDisplayName(ShippingUnit::class),
                'l2_route'      => 'shipping-unit.index',
                'permission_id' => $this->getPermissionId(ShippingUnit::class),
            ],
            [
                'level_1'       => 'Master Data',
                'l1_order'      => 1,
                'level_2'       => $this->getDisplayName(Supplier::class),
                'l2_route'      => 'suppliers.index',
                'permission_id' => $this->getPermissionId(Supplier::class),
            ],
            [
                'level_1'       => 'Administration',
                'l1_order'      => 2,
                'level_2'       => $this->getDisplayName(User::class),
                'l2_route'      => 'users.index',
                'permission_id' => $this->getPermissionId(User::class),
            ],
            [
                'level_1'       => 'Administration',
                'l1_order'      => 2,
                'level_2'       => $this->getDisplayName(Role::class),
                'l2_route'      => 'roles.index',
                'permission_id' => $this->getPermissionId(Role::class),
            ],
            // Shipping Menu
            [
                'level_1'       => 'Inbound',
                'l1_order'      => 3,
                'level_2'       => 'Purchase Orders',
                'l2_route'      => 'purchase-orders.index',
                'permission_id' => $this->getPermissionId(POHeader::class),
            ],
            [
                'level_1'       => 'Inbound',
                'l1_order'      => 3,
                'level_2'       => 'List Inbounds',
                'l2_route'      => 'inbound.index',
                'permission_id' => $this->getPermissionId(ShippingBasic::class),
            ],
            [
                'level_1'       => 'Inbound',
                'l1_order'      => 3,
                'level_2'       => 'List Inbound Banks',
                'l2_route'      => 'inbound-banks.index',
                'permission_id' => $this->getPermissionId(ShippingClearance::class),
            ],
        ];
    }

    /**
     * Get display name property of model class
     */
    protected function getDisplayName($class)
    {
        $reflection = new ReflectionClass($class);
        $instance = new $class;
        return ucwords($reflection->getProperty('display_name')->getValue($instance));
    }

    /**
     * Get short class name for the model
     */
    protected function getShortClassName($class)
    {
        return (new ReflectionClass($class))->getShortName();
    }

    /**
     * Find permission id for list action
     */
    protected function getPermissionId($class)
    {
        $name = $this->getShortClassName($class);
        $perm = $this->permissions->firstWhere('name', $name . '-list');
        return optional($perm)->id;
    }
}
