<?php

namespace App\Http\Controllers;

use App\Models\CV;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class CvController extends Controller
{
     public function deleteCV($id){
        $cv = CV::findOrFail($id);
        if (auth()->id() !== $cv->user_id) {
            return redirect()->back()->with('error', 'You cannot delete this file.');
        }
        $cv->delete();
        return back()->with('success', 'Your CV has been deleted successfully.');
    }
}
