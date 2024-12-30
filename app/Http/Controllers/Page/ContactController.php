<?php

namespace App\Http\Controllers\Page;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ContactController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('contact');
    }

    public function showForm()
    {
        return view('bid_flow.step1');
    }

    public function step1(Request $request)
    {
        $request->validate([
            'bidAmount' => 'required|numeric',
            'bidMessage' => 'required|string',
        ]);

        session([
            'bidAmount' => $request->bidAmount,
            'bidMessage' => $request->bidMessage,
        ]);

        return redirect()->route('bidFlow.step2');
    }

    public function step2(Request $request)
    {
        return view('bid_flow.step2');
    }

    public function step3(Request $request)
    {
        $request->validate([
            'paymentMethod' => 'required|string',
        ]);

        session([
            'paymentMethod' => $request->paymentMethod,
        ]);

        return redirect()->route('bidFlow.confirmation');
    }

    public function confirmation()
    {
        $bidAmount = session('bidAmount');
        $paymentMethod = session('paymentMethod');

        return view('bid_flow.confirmation', compact('bidAmount', 'paymentMethod'));
    }


    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
