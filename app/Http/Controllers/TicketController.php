<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;

class TicketController extends Controller
{

    /**
     * Display a contact page.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        return view('ticket.contact', []);
    }

    /**
     * Store a newly created ticket.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    
}
