@extends('layouts.app')

@section('title', 'Chat')

@section('content')

<div class="container w-75 mx-auto bg-light profile-page p-0 rounded">
    @if(session()->has('message'))
                    <div class="m-0 text-center alert alert-warning alert-dismissible fade show w-100 mx-auto" role="alert">
                        {{ session()->get('message') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
     @endif

     @if(session()->has('error'))
                    <div class="m-0 text-center alert alert-danger alert-dismissible fade show w-100 mx-auto" role="alert">
                        {{ session()->get('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
     @endif
     <div class="row p-3">
        @if (count($users))
            <div class="col-4 pe-0">
                <button class="btn btn-dark btn-lg w-100 rounded-0">Chat</button>
              <div class="list-group rounded-0" id="list-tab" role="tablist">
                @foreach ($users as $user)
                <a onclick="readed({{$user->id}})" class="user list-group-item list-group-item-action @if(session()->has('id') && session()->get('id') == $user->id) active : '' @endif" id="list-chat-list" data-bs-toggle="list" href="#list-chat-{{ $user->id }}" role="tab" aria-controls="list-profile">

                    @if(empty($user->user_info->folder) || empty($user->user_info->image))
                        @php $user_image = 'avatar.jpg' @endphp
                    @else
                        @php $user_image = $user->user_info->folder.'/'.$user->user_info->image @endphp
                    @endif


                     <img class="border border-danger" height="50" width="50" title="" src="{{ asset('upload/'.$user_image) }}" alt="profile-img">

                    <b class="text-muted"> {{ $user->user_info->first_name . ' '. $user->user_info->last_name }} </b>
                    @php  $msges = 0;@endphp
                    @foreach ($msgs as $msg)
                            @php
                            if(!empty($msg->readed) && $msg->readed == 'no' && $msg->sender == $user->id)

                                 $msges = $msges + 1;
                            @endphp
                        @endforeach
                    <span class="badge badge-light @if($msges > 0) bg-danger @endif">

                        {{ $msges==0 ? '' : $msges }}
                    </span>
                </a>

                @endforeach
            </div>
            </div>
            <div class="col-8 ps-1">
              <div class="tab-content rounded-0" id="nav-tabContent">
                @foreach ($users as $user)
                <div class="tab-pane fade show @if(session()->has('id') && session()->get('id') == $user->id) active : '' @endif"  id="list-chat-{{ $user->id }}" role="tabpanel" aria-labelledby="list-chat-list">
                    <h2 class="text-center text-muted">Chat met {{ $user->user_info->first_name . ' ' .  $user->user_info->last_name}}</h2>
                    <div class="d-flex justify-content-around">
                        <p class="m-1"><b>Woonplaats:</b> {{ ucfirst($user->user_info->city) }}</p>
                        <p class="m-1"><b>Leeftijd:</b> @php echo App\Http\Controllers\HomeController::getAge($user->user_info->birthday) @endphp</p>
                    </div>

                    <div id="chat-box-msg" class="w-100 border chat-box p-1">
                         @foreach ($msgs as $msg)
                            @if($msg->sender == Auth::id() && $user->id == $msg->receiver)
                            <div class="d-flex justify-content-end">
                                <div class="bg-secondary m-2 p-2 w-75 rounded">
                                    {{ $msg->msg }}
                                    <small class="d-flex justify-content-end">{{ $msg->created_at->diffForHumans() }}</small>
                                </div>
                            </div>
                            @elseif($msg->receiver == Auth::id() && $user->id == $msg->sender)
                            <div class="class d-flex justify-content-start">
                                <div class="bg-danger m-2 p-2 w-75 rounded">
                                    {{ $msg->msg }}
                                    <small class="d-flex justify-content-end">{{ $msg->created_at->diffForHumans() }}</small>
                                </div>
                            </div>
                            @endif
                        @endforeach
                    </div>
                    <form  class="w-100" action="{{ route('send.msg', $user->id) }}" method="post">
                        @csrf
                        <div class="d-flex justify-content-center w-100">
                            <input class="w-100" type="text" name="msg" value="" placeholder="Je bericht hier...">
                            <button type="submit" class="btn btn-dark rounded-0">Verzend</button>
                        </div>
                    </form>
                </div>
                @endforeach
              </div>
            </div>
        @endif

        @if(count($users) == 0)
        <h4 class="text-dark text-center">Geen match gevonden :( </h4>
        @endif
    </div>
</div>

<script>
function readed(id)
{
    $.ajax({
    type: "POST",
    url: 'msgReaded/'+ id,
    data: { _token: '{{csrf_token()}}' },
    success: function (data) {
       console.log(data);
    },
    error: function (data, textStatus, errorThrown) {
        console.log(data);
    },
});
}

</script>
@endsection
