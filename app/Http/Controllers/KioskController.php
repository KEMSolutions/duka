<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Store;

class KioskController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $slides = [];

        ## TODO Remove this dummy data and actually implement this thing.
        $slides[] = [
            "type"=>"classic",
            "url"=>"https://lapara.ca",
            "background"=>"https://images.unsplash.com/photo-1456428199391-a3b1cb5e93ab?ixlib=rb-0.3.5&q=80&fm=jpg&crop=entropy&s=db130336e8134fc3f734dbc4318f5c5e",
            "title"=>"Risus Parturient Fringilla Sem Tortor",
            "subtitle"=>"Vestibulum id ligula porta felis euismod semper."
        ];
        $slides[] = [
            "type"=>"classic",
            "url"=>"https://lapara.ca",
            "background"=>"https://images.unsplash.com/photo-1453282716202-de94e528067c?ixlib=rb-0.3.5&q=80&fm=jpg&crop=entropy&s=ac9b38e57a8a0724058cdcbe6a687aa8",
            "title"=>"Mattis Ullamcorper Vehicula",
            "subtitle"=>"Donec id elit non mi porta gravida at eget metus."
        ];

        return response()->json(["store"=>Store::info(), "kiosk"=>["url"=>route("home"), "ads"=>$slides]]);
    }

}
