<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Card;
use App\Models\Category;

class CardController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $categories = Category::with['cards']->orderBy('name', 'asc')->all();
        return view('admin.cards.index', compact('categories'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $categories = Category::all();
        return view('admin.cards.create', compact('categories'));
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
            'name' => 'required|string|max:255',
            'category' => 'required',
            'rate' => 'required|numeric|min:0',
            'min' => 'nullable|numeric|min:0',
            'max' => 'nullable|numeric|min:0',
            'type' => 'required|in:sell,buy'
        ]);

        $card = Card::create($request->merge(['category_id' => $request->category])->all());
        return redirect()->route('categories.show', $request->category)->with('success', 'Card added successfully!');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Card $card)
    {
        $categories = Category::orderBy('name', 'asc')->get();
        return view('admin.cards.edit', compact('card', 'categories'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Card $card)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'category' => 'required',
            'rate' => 'required|numeric|min:0',
            'min' => 'nullable|numeric|min:0',
            'max' => 'nullable|numeric|min:0',
            'type' => 'required|in:sell,buy'
        ]);

        $card->update($request->merge(['category_id' => $request->category])->all());
        return redirect()->route('categories.show', $request->category)->with('success', 'Changes saved successfully!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Card $card)
    {
        if ($card->transactions()->count() > 0) return back()->with('warning', 'Card cannot be removed!');
        $card->delete();
        return back()->with('success', 'Card successfully removed!');
    }
}