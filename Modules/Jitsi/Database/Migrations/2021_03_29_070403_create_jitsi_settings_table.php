<?php
use App\SmLanguagePhrase;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schema;
use Modules\Jitsi\Entities\JitsiSetting;
use Modules\MenuManage\Entities\Sidebar;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Modules\RolePermission\Entities\InfixModuleInfo;
use Modules\RolePermission\Entities\InfixPermissionAssign;
use Modules\RolePermission\Entities\InfixModuleStudentParentInfo;

class CreateJitsiSettingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        Schema::create('jitsi_settings', function (Blueprint $table) {
            $table->id();
            $table->string('jitsi_server')->default('https://meet.jit.si/');            
            $table->timestamps();
        });

        $s = new JitsiSetting();      
        $s->jitsi_server = 'https://meet.jit.si/';      
        $s->save();


    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('jitsi_settings');
    }
}
