<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Withdrawal;

class WithdrawalController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $withdrawals = Withdrawal::desc()->paginate(25);
        return view('admin.withdrawals.index', compact('withdrawals'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'admin_comment' => 'nullable|string',
            'payment_status' => 'nullable|in:failed,pending,succeed'
        ]);


        $transaction->update($request->all());

        return back()->with('info', 'Changes saved!');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Withdrawal $withdrawal)
    {
        return view('admin.withdrawals.show', compact('withdrawal'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Withdrawal $withdrawal)
    {
        $p_s = array('failed', 'pending', 'succeed');

        if (!in_array($request->payment_status, $p_s)) {
            $request->merge(['payment_status' => $transaction->payment_status]);
        }

        $request->validate([
            'admin_comment' => 'nullable|string',
            'payment_status' => 'nullable|in:failed,pending,succeed'
        ]);


        $withdrawal->update($request->all());

        return back()->with('info', 'Withdrawal updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}