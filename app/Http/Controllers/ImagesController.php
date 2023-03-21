<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ImagesController extends Controller
{
    /**
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function storeImage(Request $request)
    {


        // TODO HF: Hibakezelés megoldani

        // Upload
        $image_path = $request->file('image_url')->store('uploads', 'public_uploads');

        // File pathe register
        $userId = $request->get('id');
        $user = Auth::user();

        if(!empty($userId)) {
            $user = User::find($userId);
        }

        $user->image_file = $image_path;
        $user->save();

        return redirect()->route('profile.edit', ['id' => $userId]);
    }
}