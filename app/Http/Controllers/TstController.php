<?php
namespace App\Http\Controllers;



class TstController extends BaseController {

    public function tst() {

        \Illuminate\Support\Facades\Storage::disk('local');
        //dd(app());
        $res = \Illuminate\Support\Facades\DB::select('SELECT PG_BACKEND_PID();');
        dump($res);
        return 'TSTOK';
    }
}
