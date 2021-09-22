<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Artisan;
use Session;
use Auth;

class DatabaseController extends Controller
{
    function userDB(){

        //USER EMAIL + RANDOM NUMBER
        $auth_email=Auth::user()->db_name;

        $query = "SELECT SCHEMA_NAME FROM INFORMATION_SCHEMA.SCHEMATA WHERE SCHEMA_NAME =  ?";
        $db = DB::select($query, [$auth_email]);
        if (empty($db)) {
            //CREATE DATABASE
            DB::statement("CREATE DATABASE {$auth_email}");

            //CONFIG NEW TABLE
            config(["database.connections.$auth_email" => [
                'driver'    => 'mysql',
                'host'      => '127.0.0.1',
                'port'      => '3306',
                'database'  => "{$auth_email}",
                'username'  => 'root',
                'password'  => 'Aa1234567',
            ]]);

            //USE THE NEW CACHE
            \Illuminate\Support\Facades\DB::purge("{$auth_email}");

            //MIGRATE ALL DATA
            //\Illuminate\Support\Facades\Artisan::call('migrate', ['--database' => "{$auth_email}"]);

            //CREATE TABLES
            Schema::connection("{$auth_email}")->create('products', function($table)
            {
                $table->increments('id');
                $table->string('name');
                $table->integer('category_id');
                $table->decimal('price');
                $table->timestamps();
            });

            Schema::connection("{$auth_email}")->create('categories', function ($table) {
                $table->increments('id');
                $table->string('name');
                $table->timestamps();
            });

            //SEED TABLES
            Artisan::call("db:seed", ['--class' => 'UserDBSeeder','--database' => "{$auth_email}"]);

            } 

    return redirect('show');
    }

    function show(){

        $auth_email = Auth::user()->db_name;
        $user = Auth::user()->name;
        config(["database.connections.$auth_email" => [
            'driver'    => 'mysql',
            'host'      => '127.0.0.1',
            'port'      => '3306',
            'database'  => "{$auth_email}",
            'username'  => 'root',
            'password'  => 'Aa1234567',
        ]]);
         //USE THE NEW CACHE
        DB::purge("{$auth_email}");

        $products = (object)DB::connection("{$auth_email}")->select('table products');
        $categories = DB::connection("{$auth_email}")->select('table categories');
        return view('master',['user'=>$user,'products'=>$products,'categories'=>$categories]);
    }
}
