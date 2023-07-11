<?php

namespace App\Http\Controllers;

use App\Models\Chat;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Carbon;
use App\Models\Matching;
use App\Models\User;
use App\Models\User_images;
use App\Models\User_tags;
use Illuminate\Support\Facades\Session;

class HomeController extends Controller
{
    /**
     * Redirect to dashboard based on role
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (Auth::check()) {
            return $this->dashboard();
        }
        return view('master.home');
    }

    /**
     * Redirect to dashboard based on role
     *
     * @return \Illuminate\Http\Response
     */
    public function dashboardAdmin()
    {
        $user = User::with('user_info')->get()->first()->toArray();
        return view('layouts.dashboard')->with(['user' => $user]);
    }

    /**
     * Redirect to dashboard based on role after login
     *
     * @return \Illuminate\Http\Response
     */
    public function dashboard()
    {
        if (Auth::user()->role == 'admin') {
            return $this->dashboardAdmin();
        }

        if (User::count() < 2)
        {
            return view('master.digiDate')->with(['user' => '', 'user_tags' => '']);
        }

        if (Auth::user()->role == 'user' || Auth::user()->role == 'adminUser' ) {

            for ($i = 0; $i < 20; $i++) {
                $user = $this->getUser();

                if ($this->checkPref($user) && $this->checkStatus($user->id)) {
                    $user_tags = [];

                    $user_tag = User_tags::where('user_id', $user->id)->get();

                    foreach ($user_tag as $tag_info) {
                        $user_tags[] = $tag_info->tag->name;
                    }

                    $user_image  = User_images::where('user_id', $user->id)->where('status', 'primary')->get()->first();

                    if ($user_image)
                    {
                        $user_image =  $user_image->folder . '/' . $user_image->image;
                    }


                    return view('master.digiDate')->with([
                        'user' => $user,
                        'user_tags' => $user_tags,
                        'user_image' => $user_image,
                    ]);
                }
            }

            return view('master.digiDate')->with(['user' => '', 'user_tags' => '']);
        }
        return view('master.digiDate')->with(['user' => '', 'user_tags' => '']);

    }

    // Get a random user + escape last one using Session
    public function getUser()
    {
        if (Session::get('lastuser'))
        {
            $last_user =  Session::get('lastuser');
        } else
        {
            $last_user = Auth::id();
        }


        $user = User::where('id', '!=', Auth::id())
            ->whereIn('role', ['user','adminUser'])
            ->where('id', '!=', $last_user)
            ->get()
            ->random(1)
            ->first();

        Session::put('lastuser', $user);
        return $user;
    }

    // get user match notifications amont on above menu
    public static function getNofic()
    {
        if (Auth::check() && Auth::user()->role !== 'admin')
        {
            $senders = Matching::where('receiver', Auth::id())
            ->where('status' , 'wait')
            ->get();

             return count($senders);
        }

        // Set 0 as default value
        return 0;
    }

    // get user chat notifications amont on above menu
    public static function getNoficMsges()
    {
        if (Auth::check() && Auth::user()->role !== 'admin')
        {
            $senders = Chat::where('receiver', Auth::id())
            ->where('readed' , 'no')
            ->get();

             return count($senders);
        }

        // Set 0 as default value
        return 0;
    }



    // Check pref of two users
    public function checkPref($user)
    {
        $current_user = User::where('id', Auth::id())
            ->whereIn('role', ['user','adminUser'])
            ->get()
            ->first();

        $userAge = HomeController::getAge($user->user_info->birthday);


        // Check if rejected
        $matching = Matching::where('sender', Auth::id())->orWhere('receiver',  Auth::id())->get();

        foreach ($matching as $match) {
            if ($match->status == 'rejected' && ($user->id == $match->sender || $user->id == $match->receiver)) {
                return false;
            }
        }


        $current_user->user_preferences->city ? $checkCity = false : $checkCity = true;
        $current_user->user_preferences->gender ? $checkGender = false : $checkGender = true;


        if (!$checkCity)
        {
            if ($user->user_info->city == $current_user->user_preferences->city)
            {
                $checkCity = true;
            }
        }

        if (!$checkGender)
        {
            if ($user->user_info->gender == $current_user->user_preferences->gender)
            {
                $checkGender = true;
            }
        }

        if (
            $checkGender
            &&  $checkCity
            &&  ($userAge >= $current_user->user_preferences->age_min)
            &&  ($userAge <= $current_user->user_preferences->age_max)
        ) {
            if ($this->checkTags($user->id)) {
                return true;
            }
        }

        return false;
    }

    // Check if one of tags is exist .. if not it escape tags
    public function checkTags($id)
    {
        $user_tags = User_tags::where('user_id', $id)->get()->toArray();

        $current_user_tags = User_tags::where('user_id', Auth::id())->get()->toArray();

        if (!$current_user_tags) {
            return true;
        }

        $tags = [];
        foreach ($user_tags as $usertag) {
            $tags[] = $usertag['tag_id'];
        }

        $user_tags = [];
        foreach ($current_user_tags as $tag) {
            $user_tags[] = $tag['tag_id'];
        }


        if (array_intersect($user_tags, $tags)) {
            return true;
        }

        return false;
    }

    // Check if user already a request sent
    public function checkStatus($id)
    {
        $check_sent = Matching::where('receiver', Auth::id())
            ->where('sender', $id)
            ->get()
            ->first();

        $check_rec = Matching::where('sender', Auth::id())
            ->where('receiver', $id)
            ->get()
            ->first();

        if (!$check_rec && !$check_sent) {
            return true;
        }

        return false;
    }

    // Convert birthday to age
    public static function getAge($birthday)
    {
        return Carbon::parse($birthday)->age;;
    }

    // Delet Session after finishing
    public function __destruct()
    {
        Session::get('lastuser') ?? Session::forget('lastuser') ;
    }
}
