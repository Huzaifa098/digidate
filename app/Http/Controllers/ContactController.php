<?php

namespace App\Http\Controllers;

use App\Models\Contact;
use App\Models\User;
use App\Models\User_info;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ContactController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user = User::with('user_info')->get()->first()->toArray();
        $messages = Contact::all();
        return view('admin.contact')->with(['user' => $user,'messages' => $messages]);;
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if ( Auth::check())
        {
            $user = User::findOrFail(Auth::id(), ['email']);
            $user_info = User_info::findOrFail(Auth::id(), ['first_name', 'last_name']);
            return view('master.contact')->with(['email' => $user->email, 'full_name' => $user_info->first_name . ' ' . $user_info->last_name]);
        }
        return view('master.contact');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        $validated = $request->validate([
            'name' => 'required|max:255',
            'subject' => 'required|max:255',
            'email' => 'required|email',
            'message' => 'required|max:255',
        ]);

        Contact::create($validated);

        return redirect()->back()->with('message', 'Uw bericht is succesvol verzonden. We nemen heel snel contact met je op!');
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
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $messege = Contact::find($id);
        $messege->delete();
        return redirect()->back()->with('message', 'Bericht Verwijderd!');
    }
}
