<?php

namespace App\Http\Controllers;

use App\Models\Tags;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TagController extends Controller
{
      /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user = User::with('user_info')->get()->first()->toArray();
        $user_tags = User::with('tags', 'user_info')
        ->where('role', 'admin')
        ->orWhere('role', 'adminUser')
        ->get()
        ->first()
        ->toArray();
        return view('admin.tags')->with(['user_tags' => $user_tags, 'user'=> $user]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $user = User::with('user_info')->get()->first()->toArray();
        return view('admin.tags.create')->with(['user'=> $user]);;
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $user = User::with('user_info')->get()->first()->toArray();
        $tag = Tags::where('id', $id)->get()->first();
        return view('admin.tags.edit')->with(['user'=> $user, 'tag'=> $tag]);
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
            'name' => 'required|max:50|unique:tags',
        ]);


        Tags::create([
            'name' => $request->name,
            'admin_id' => Auth::id(),
        ]);

        return $this->index();
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $tag = Tags::where('id', $id)->firstOrFail();

        $request->validate([
            'name' => 'required|max:50|unique:tags,name,'.$id.',id',
        ]);

        $tag->name = $request->name;
        $tag->admin_id = Auth::id();
        $tag->save();

        return $this->index();

    }

     /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $tag = Tags::find($id);
        $tag->delete();
        return redirect()->back()->with('message', 'Tag verwijderd!');
    }
}
