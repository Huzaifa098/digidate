<?php

namespace App\Http\Controllers;

use App\Models\Tags;
use App\Models\User;
use App\Models\User_images;
use App\Models\User_info;
use App\Models\User_preferences;
use App\Models\User_tags;
use App\Rules\CheckAge;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use Illuminate\Validation\Rules\Password;

class ProfileController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user  = User::where('id', Auth::id())->get()->first();

        $tags = Tags::all();
        $user_tags =  User_tags::where('user_id', Auth::id())->get();
        if ($user_tags)
        {
            $user_tags = $user_tags->toArray();
        }

        $user_images  = User_images::where('user_id', Auth::id())->where('status', 'primary')->get()->first();
        if($user_images)
        {
            $user_image = $user_images->folder . '/' . $user_images->image;
        }
        else {
            $user_image = 'notFound';
        }

        $all_images  = User_images::where('user_id', Auth::id())->orderBy('status', 'asc')->get();

        return view('user.profile')->with([
            'user' => $user,
            'tags' => $tags,
             'user_tags' => $user_tags,
             'user_image' => $user_image,
             'normal_images' => $all_images
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function updateProfile(Request $request)
    {
        $user = User_info::where('user_id', Auth::id())->firstOrFail();

        $data = $request->validate([
            'image'     => 'nullable|mimes:jpg,png,jpeg,gif,svg',
            'bio'       => 'nullable|max:100',
        ]);

        if($request->file('image'))
        {
            $check_images_amount = User_images::where('user_id', Auth::id())->get()->toArray();
            if (count($check_images_amount) >= 5)
            {
                return redirect()->back()->with('error', 'Je kunt maximaal 5 foto\'s uploaden!');
            }

            $check_images_status = User_images::where('user_id', Auth::id())->get()->first();

            if(!$check_images_status)
            {
                $folder = random_int(100000, 999999);
                $status = 'primary';
            }else {
                $status = 'normal';
                $folder = $user->user->user_images[0]->folder;
            }

            $file= $request->file('image');
            $filename = random_int(100000, 999999) . $file->getClientOriginalName();
            $filename = str_replace(' ', '', $filename);
            $file->move(public_path('/upload/'. $folder .'/'), $filename);
            $data['image']= $filename;

            User_images::create([
                'folder' => $folder,
                'user_id' => Auth::id(),
                'image' => $data['image'],
                'status' => $status
            ]);
        }

        $user->bio = $data['bio'];
        $user->save();

        return redirect()->back()->with('message', 'Profile geupdated!');
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function updatePersonal(Request $request)
    {
        $user_info = User_info::where('user_id', Auth::id())->firstOrFail();

        $data = $request->validate([
            'first_name' => 'required|max:30',
            'middle_name' => 'nullable|max:30',
            'last_name' => 'required|max:30',
            'city' => 'required|max:30',
            'phone' => 'required|numeric|regex:/(06)[0-9]/|digits:10',
            'birthday' => ['required', 'date', new CheckAge],
            'gender' => 'required|max:10|in:man,women',
        ]);

        $user_info->first_name = $data['first_name'];
        $user_info->middle_name = $data['middle_name'];
        $user_info->last_name = $data['last_name'];
        $user_info->city = $data['city'];
        $user_info->phone = $data['phone'];
        $user_info->birthday = $data['birthday'];
        $user_info->gender = $data['gender'];

        $user_info->save();

        return redirect()->back()->with('message', 'Persoonlijk gegevens geupdated');
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function updatePref(Request $request)
    {

        $user_pref = User_preferences::where('user_id', Auth::id())->first();

        if (!$user_pref)
        {
            User_preferences::create(['user_id' => Auth::id()]);
        }

        $data = $request->validate([
            'gender'    => 'nullable|in:man,women|max:10',
            'city'      => 'nullable|max:50',
            'age_min'   => 'nullable|numeric',
            'age_max'   => 'nullable|numeric',
            'tags'      => 'nullable|array'
        ]);

        if ( $data['age_max']  <= $data['age_min']) {
            return back()->with('error', 'Voorkeur leeftijd maximaal moet meer dan minimaal');
        }

        $user_pref->gender = $request->gender;
        $user_pref->city = $request->city;
        $user_pref->age_min = $data['age_min'];
        $user_pref->age_max = $data['age_max'];

        $user_pref->save();

        $user_tags = $request->only(['tags']);


        // remove tags
        User_tags::where('user_id', Auth::id())->delete();

        if (!empty($user_tags['tags'])) {
            foreach ($user_tags['tags'] as $tag) {
                User_tags::firstOrCreate([
                    'user_id' => Auth::id(),
                    'tag_id' => $tag,
                ]);
            }
        }

        return redirect()->back()->with('message', 'Voorkeuren geupdated');
    }


    /**
     * Remove the specified resource from storage.
     *
     *
     *
     */
    public function destroy()
    {

        $user = User::find(Auth::id());

        // Delete user images
        $images  = User_images::where('user_id', $user->id)->get()->first();


        if ($images)
        {
            File::deleteDirectory(public_path('/upload/'.$images->folder));
        }

        $user->delete();
        Session::flush();

        return redirect()->route('home')->with('message', 'Account verwijderd!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     *
     */
    public function deleteImage($id)
    {
        $image_public = User_images::where('id', $id)->get()->first();
        $deleted = unlink('upload/' .$image_public->folder .'/'.$image_public->image);

        if($deleted)
        {
            $image = User_images::find($id);
            $image->delete();
        } else {
            return redirect()->back()->with('error', 'Foto kan niet verwijderd, probeer later!');
        }

        return redirect()->back()->with('message', 'Foto verwijderd!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function setImageToPrimary($id)
    {
        $all_images  = User_images::where('user_id', Auth::id())->get();

        foreach($all_images as $image)
        {
            $image->status = 'normal';

            if ($image->id == $id)
            {
                $image->status = 'primary';
            }

            $image->save();
        }


        return redirect()->back()->with('message', 'Profile foto updated!');
    }

    public function showEmailEditForm()
    {
       return view('reset.reset_email');
    }

    public function emailEditSubmit(Request $request)
    {
        if(!Auth::check())
        {
            return redirect()->route('home');
        }

        $request->validate([
            'email' => 'required|email|unique:users',
            'password' => 'required|confirmed'
        ]);

        $user = User::where('email', Auth::user()->email)->first();

        if ($user && Hash::check($request->password, $user->password))
        {
            $user->email = $request->email;
            if ($user->save())
            {
                Auth::logout();
                Session::flush();
                return redirect()->route('home')->with('message', 'U email is gewijzegd, je kunt opnieuw inloggen met uw nieuwe email');
            }
            return redirect()->back()->with('error', 'Er is iets mis gegaan, probeer later!');
        }
        return redirect()->back()->with('error', 'Je gegvens is niet juist!');

    }

}
