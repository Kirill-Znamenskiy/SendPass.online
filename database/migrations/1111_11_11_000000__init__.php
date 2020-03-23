<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Storage;

class InitKzTrfunctions extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        /** @var \Illuminate\Filesystem\FilesystemAdapter $local_fs */
        $local_fs = Storage::getFacadeRoot()->createLocalDriver(['root' => app('path.database')]);
        DB::unprepared($local_fs->get('./schemas/public/__init__.sql'));
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
