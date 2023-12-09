<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ImageController extends Controller
{
    // View File To Upload Image
    public function index()
    {
        return view('show');
    }

    // Store Image
    public function storeImage(Request $request)
    {
        $request->validate([
            'image' => 'required|image|mimes:png,jpg,jpeg|max:2048'
        ]);
    
        $imageName = time().'.'.$request->image->extension();
    
        // Move the image to the public directory
        $request->image->move(public_path('images'), $imageName);
    
        return back()->with('success', 'Image uploaded Successfully!')
                     ->with('image', $imageName);
    }
}