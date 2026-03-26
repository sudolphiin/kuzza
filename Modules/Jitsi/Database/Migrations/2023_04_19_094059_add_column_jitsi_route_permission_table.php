<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Modules\RolePermission\Entities\Permission;
use Modules\RolePermission\Entities\InfixModuleInfo;

class AddColumnJitsiRoutePermissionTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $routeList = array(  816 => 
        array (
          'name' => 'Jitsi',
          'route' => 'jitsi',
          'parent_route' => NULL,
          'type' => 1,
          'lang_name' => 'jitsi::jitsi.jitsi',
          'icon' => 'flaticon-reading'
        ),
        817 => 
        array (
          'name' => 'Virtual Class',
          'route' => 'jitsi.virtual-class',
          'parent_route' => 'jitsi',
          'type' => 2,
          'lang_name' => 'jitsi::jitsi.virtual_class'
        ),
        818 => 
        array (
          'name' => 'Add',
          'route' => 'jitsi.virtual-class.store',
          'parent_route' => 'jitsi.virtual-class',
          'type' => 3,
        ),
        819 => 
        array (
          'name' => 'Edit',
          'route' => 'jitsi.virtual-class.edit',
          'parent_route' => 'jitsi.virtual-class',
          'type' => 3,
        ),
        820 => 
        array (
          'name' => 'Delete',
          'route' => 'jitsi.virtual-class.destroy',
          'parent_route' => 'jitsi.virtual-class',
          'type' => 3,
        ),
        821 => 
        array (
          'name' => 'Start Class',
          'route' => 'jitsi.class.start',
          'parent_route' => 'jitsi.virtual-class',
          'type' => 3,
        ),
        822 => 
        array (
          'name' => 'Virtual Meeting',
          'route' => 'jitsi.meetings',
          'parent_route' => 'jitsi',
          'type' => 2,
          'lang_name' => 'jitsi::jitsi.virtual_meeting'
        ),
        823 => 
        array (
          'name' => 'Add',
          'route' => 'jitsi.meetings.store',
          'parent_route' => 'jitsi.meetings',
          'type' => 3,
        ),
        824 => 
        array (
          'name' => 'Edit',
          'route' => 'jitsi.meetings.edit',
          'parent_route' => 'jitsi.meetings',
          'type' => 3,
        ),
        825 => 
        array (
          'name' => 'Delete',
          'route' => 'jitsi.meetings.destroy',
          'parent_route' => 'jitsi.meetings',
          'type' => 3,
        ),
        826 => 
        array (
          'name' => 'Start Meeting',
          'route' => 'jitsi.meeting.join',
          'parent_route' => 'jitsi.meetings',
          'type' => 3,
        ),
        827 => 
        array (
          'name' => 'Class Report',
          'route' => 'jitsi.virtual.class.reports.show',
          'parent_route' => 'jitsi',
          'type' => 2,
          'lang_name' => 'jitsi::jitsi.class_reports'
        ),
        828 => 
        array (
          'name' => 'Search',
          'route' => '',
          'parent_route' => 'jitsi.virtual.class.reports.show',
          'type' => 3,
        ),
        829 => 
        array (
          'name' => 'Meeting Report',
          'route' => 'jitsi.meeting.reports.show',
          'parent_route' => 'jitsi',
          'type' => 2,
          'lang_name' => 'jitsi::jitsi.meeting_reports'
        ),
        830 => 
        array (
          'name' => 'Search',
          'route' => '',
          'parent_route' => 'jitsi.meeting.reports.show',
          'type' => 3,
        ),
        831 => 
        array (
          'name' => 'Settings',
          'route' => 'jitsi.settings',
          'parent_route' => 'jitsi',
          'type' => 2,
          'lang_name' => 'jitsi::jitsi.settings'
        ),
        832 => 
        array (
          'name' => 'Update',
          'route' => 'jitsi.settings.update',
          'parent_route' => 'jitsi.settings',
          'type' => 3,
        ),
        );

        foreach($routeList as $key=>$item){
          Permission::updateOrCreate([
                'old_id'=>$key,
                'route'=>$item['route'],
                'parent_route'=>$item['parent_route'],
            ],
            [
                'module'=>'Jitsi',
                'name'=>$item['name'],
                'is_admin'=>1,
                'type'=>$item['type'],
                'lang_name'=>isset($item['lang_name']) ? $item['lang_name'] : null,
                'icon'=>isset($item['icon']) ? $item['icon'] : null,
                'is_menu'=> $item['type'] == 1 || $item['type'] ==2 ? 1 : 0,
            ]
            );
        }

        // Schema::table('jitsi_virtual_classes', function (Blueprint $table) {
        //   if(!Schema::hasColumn($table->getTable(), 'shift_id')) {
        //       $table->integer('shift_id')->nullable()->after('end_time');
        //   }
        // });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {

    }
}
