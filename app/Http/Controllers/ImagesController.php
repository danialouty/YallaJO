<?php

namespace App\Http\Controllers;

use App\Models\Adventure;
use Illuminate\Http\Request;
use App\Models\Images;

class ImagesController extends Controller
{
    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
        $request->validate([
            'adventure_id'=>'required',
            'image_path'=>'required|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);
        $input = $request->all();
    
        if ($image = $request->file('image_path')) {
            $destinationPath = 'images/';
            $profileImage = date('YmdHis') . "." . $image->getClientOriginalExtension();
            $image->move($destinationPath, $profileImage);
            $input['image_path'] = "$profileImage";
        }

        Adventure::create($input);
       
        return redirect()->route('adventures.index')
                         ->with('success', 'Adventure created successfully.');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Adventure $image)
    {
        //
        $request->validate([
            'adventure_id'=>'required',
        ]);

        $input = $request->all();
    
        if ($image = $request->file('image_path')) {
            $destinationPath = 'images/';
            $profileImage = date('YmdHis') . "." . $image->getClientOriginalExtension();
            $image->move($destinationPath, $profileImage);
            $input['image_path'] = "$profileImage";
        }else{
            unset($input['image_path']);
        }
            
        $image->update($input);

        return redirect()->route('adventures.index')
            ->with('success', 'Adventure updated successfully');
    }

}
