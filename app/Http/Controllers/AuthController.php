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
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use Illuminate\Validation\Rules\Password;

class AuthController extends Controller
{

     /**
     * Login User.
     */
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email|max:255',
            'password' => 'required|max:255',
        ]);

        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials))
        {
            return redirect()->route('dashboard');
        }

        return redirect()->back()->with('message', 'Your email or password not correct!');
    }

    /**
     * Logout
     */
    public function logout()
    {
        Auth::logout();
        Session::flush();
        return redirect()->route('home');
    }


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $users = User::with('user_info')->where('role', 'user')->get();
        $user = User::with('user_info')->get()->first()->toArray();
        return view('admin.users')->with(['users' => $users, 'user' => $user]);;
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
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function register_create()
    {
        return view('master.register');
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
            'email' => 'required|email|unique:users',
            'password' =>
            [
                'required','confirmed',
                 Password::min(8)
                               ->mixedCase()
                               ->letters()
                               ->numbers()
                               ->symbols()
                               ->uncompromised(),
            ],
            'first_name' => 'required|max:30',
            'middle_name' => 'nullable|max:30',
            'last_name' => 'required|max:30',
            'city' => 'required|max:30',
            'phone' => 'required|numeric|regex:/(06)[0-9]/|digits:10',
            'birthday' => ['required', 'date', new CheckAge],
            'gender' => 'required|max:10|in:man,women',
        ]);

        $user_inster = User::create([
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'user',
        ]);

        $lastInsertedId = $user_inster->id;
        $user_info = $request->only(['first_name','middle_name','last_name','city','phone','birthday','gender']);

        $user_info['user_id'] = $lastInsertedId;
        $user_info['phone'] = $user_info['phone'];
        User_info::create($user_info);

        $credentials = $request->only('email', 'password');
        if (Auth::attempt($credentials)) {
            return $this->register_finish_page();
        }

        return redirect()->route('register');
    }


    /**
     * Redirect to register finish page
     *
     */
    public function register_finish_page()
    {
        $tags = Tags::all();
        return view('master.register_finish')->with(['tags' => $tags]);
    }


    /**
    * Register finish => update user info
    *
    * @param  \Illuminate\Http\Request  $request
    * @param  int  $id
    * @return \Illuminate\Http\Response
    */
    public function register_finish_submit(Request $request)
    {
        $user_id = Auth::id();

        $user = User_info::where('user_id', $user_id)->firstOrFail();

        $data = $request->validate([
            'image'     => 'nullable|mimes:jpg,png,jpeg,gif,svg',
            'bio'       => 'nullable|max:100',
            'gender'    => 'nullable|in:man,women|max:10',
            'city'      => 'nullable|max:50',
            'age_min'       => 'nullable|numeric',
            'age_max'       => 'nullable|numeric',
            'tags'      => 'nullable|array',
        ]);

        if($request->file('image'))
        {
            $file= $request->file('image');
            $filename = random_int(100000, 999999) . $file->getClientOriginalName();
            $filename = str_replace(' ', '', $filename);
            $randomNumber = random_int(100000, 999999);
            $file->move(public_path('/upload/'. $randomNumber.'/'), $filename);
            $data['image']= $filename;

            User_images::create([
                'folder' => $randomNumber,
                'user_id' => $user_id,
                'image' => $data['image'],
                'status' => 'primary'
            ]);
        }

        $user->bio = $request->bio;

        $user_prefs = $request->only(['age_min','age_max','city','gender']);

        if ($request['age_max'] <= $request['age_min']) {
            $request['age_max'] = '';
            $request['age_min'] = '';
        }

        $user_prefs['user_id'] = $user_id;

        User_preferences::create($user_prefs);

        $user_tags = $request->only(['tags']);

        if ($user_tags)
        {
            foreach ($user_tags['tags'] as $tag)
            {
                User_tags::create([
                    'user_id' => $user_id,
                    'tag_id' => $tag,
                ]);
            }
        }


        if ($user->save())
        {
            return redirect()->route('home')->with('message', 'Bedankt voor het aanmelden. U kunt inloggen om van onze community te genieten.');
        }

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
    public function edit($id)
    {
        $admin = User::with('user_info')->where('id', Auth::id())->get()->first()->toArray();
        $userr = User::with('user_info')->where('id', $id)->get()->first();
        return view('admin.users.edit')->with(['userr' =>$userr , 'user' => $admin]);
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
        $user_info = User_info::where('user_id', $id)->firstOrFail();

        $data = $request->validate([
            'first_name' => 'required|max:30',
            'middle_name' => 'required|max:30',
            'last_name' => 'required|max:30',
            'email' => 'required|email|unique:users,email,'.$id.',id',
            'password' =>
            [
                'nullable','confirmed',
                 Password::min(8)
                               ->mixedCase()
                               ->letters()
                               ->numbers()
                               ->symbols()
                               ->uncompromised(),
            ],
        ]);

        $user_info->first_name = $data['first_name'];
        $user_info->middle_name =  $data['middle_name'];
        $user_info->last_name =  $data['last_name'];
        $user_info->save();

        $user = User::where('id', $id)->firstOrFail();
        $user->email = $data['email'];
        if ($data['password'] ?? false)
        {
            $user->password = Hash::make($data['password']);
        }
        $user->save();

        return $this->index();
    }

    /**
     *
     */
    public function switchTo()
    {

        $user = User::find(Auth::id());
        if ($user->role == 'admin')
        {
            $user->role = 'adminUser';
            $user->save();
            return redirect()->route('home')->with('message' , 'Account switched');
        }

        if ($user->role == 'adminUser')
        {
            $user->role = 'admin';
            $user->save();
            return redirect()->route('home')->with('message' , 'Account switched');
        }

        return redirect()->back()->with('error' , 'Er is een fout gegaan!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        if ($id !== Auth::id())
        {
            $user = User::find($id);
            $user->delete();
            Session::flush();
            return redirect()->back()->with('message', 'Gebruiker account verwijderd!');
        }
        return redirect()->back()->with('message', 'Je kunt jou account niet verwijderen!');
    }
}
