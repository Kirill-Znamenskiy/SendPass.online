<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSecretsTable extends Migration
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
        DB::unprepared($local_fs->get('./schemas/public/secret.sql'));
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('secret');
    }
}
