<?php

namespace App\Http\Controllers;

use App\Models\Chat;
use App\Models\Matching;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MatchingController extends Controller
{
    // Get matched users
    public function index()
    {

        $senders = Matching::where('receiver', Auth::id())
        ->where('status' , 'wait')
        ->get();

        foreach($senders as $sender)
        {
            $sender->userSender;
        }

        return view('user.match')->with(['senders' => $senders]);
    }

    // Get Chat
    public function indexChat()
    {

        $sent = Matching::where('status' , 'accepted')
        ->where('sender' , Auth::id())
        ->orderBy('created_at')
        ->get();

        $received = Matching::where('status' , 'accepted')
        ->where('receiver' , Auth::id())
        ->get();


        $chat = [];

        foreach($received as $sender)
        {
            $chat[] = $sender->userSender;
        }

        foreach($sent as $receiver)
        {
            $chat[] = $receiver->userReceiver;
        }

        if(!empty($chat))
        {
            foreach ($chat as $index => $item) {

                if($item->id == Auth::id())
                unset($chat[$index]);
            }
        }

        $messages = Chat::where('receiver', Auth::id())->orWhere('sender', Auth::id())->orderBy('created_at')->get();
        return view('user.chat')->with(['users' => $chat, 'msgs' => $messages]);
    }

    // Making like
    public function like($id)
    {
        $check_sent = Matching::where('receiver', Auth::id())
        ->where('sender', $id)
        ->get()
        ->first();

        $check_rec = Matching::where('sender', Auth::id())
        ->where('receiver', $id)
        ->get()
        ->first();

        if(!$check_rec && !$check_sent)
        {
            Matching::create([
                'sender' => Auth::id(),
                'receiver' => $id,
                'status' => 'wait'
            ]);
        }
        else {
            if ($check_sent)
            {
                $check_sent->status = 'accepted';
                $check_sent->save();
            }
            elseif($check_sent)
            {
                $check_rec->status = 'accepted';
                $check_rec->save();
            }

        }

        return redirect()->route('home');
    }

     // Making dislike
    public function disLike($id)
    {
        $check_sent = Matching::where('receiver', Auth::id())
        ->where('sender', $id)
        ->get()
        ->first();

        $check_rec = Matching::where('sender', Auth::id())
        ->where('receiver', $id)
        ->get()
        ->first();

        if(!$check_rec && !$check_sent)
        {
            Matching::create([
                'sender' => Auth::id(),
                'receiver' => $id,
                'status' => 'rejected'
            ]);
        }
        else {
            if ($check_sent)
            {
                $check_sent->status = 'rejected';
                $check_sent->save();
            }
            elseif($check_sent)
            {
                $check_rec->status = 'rejected';
                $check_rec->save();
            }

        }

        return redirect()->route('home');
    }


    // Send message to user
    public function sendMsg(Request $request, $id)
    {
        $request->validate([
            'msg'      => 'nullable',
        ]);

        $msg = $request->msg;

        if (empty($request->msg))
        {
            $msg = ' ';
        }


        Chat::create([
            'sender' => Auth::id(),
            'receiver' => $id,
            'msg' => $msg,
            'readed' => 'no',
        ]);

        return redirect()->back()->with('id', $id);
    }

    public function msgReaded($id)
    {
        $readed = Chat::where('receiver', Auth::id())
        ->where('sender', $id)
        ->get();

        foreach($readed  as $row)
        {
            $row->readed = 'yes';
            $row->save();

        }
        return;
    }
}
