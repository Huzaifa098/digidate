@extends('layouts.app')

@section('title', 'Match')

@section('content')

<div class="container w-50 mx-auto bg-light profile-page p-0 rounded">
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
        @foreach ($senders as $sender)
            <div class="d-flex justify-content-between mx-auto">
                @if(empty($sender->userSender->user_info->folder) || empty($sender->userSender->user_info->image))
                @php $user_image = 'avatar.jpg' @endphp
                @else
                @php $user_image = $sender->userSender->user_info->folder.'/'.$sender->userSender->user_info->image @endphp
                @endif
                <img class="border border-danger" height="150" width="200" title="" src="{{ asset('upload/'.$user_image) }}" alt="profile-img"></a>
                <div class="align-self-center">
                    <h3 class="text-dark">{{ ucfirst($sender->userSender->user_info->first_name) . ' ' .  $sender->userSender->user_info->last_name}}</h3>
                    <h5 class="text-dark">Woonplaats: {{ ucfirst($sender->userSender->user_info->city)}}</h5>
                    @php
                        $age = App\Http\Controllers\HomeController::getAge($sender->userSender->user_info->birthday);
                    @endphp
                    <h5 class="text-dark">Leeftijd: {{ $age}}</h5>
                </div>
                <div class="d-flex justify-content-start align-self-center">
                    <form action="{{ route('like', $sender->sender) }}" method="post" class="m-2">
                        @csrf
                        <button type="submit" class="border-0 bg-transparent"><i class='bx bxs-heart bx-md text-danger'></i></button>
                    </form>

                    <form action="{{ route('dislike', $sender->sender) }}" method="post" class="m-2">
                        @csrf
                        <button type="submit" class="border-0 bg-transparent"><i class='bx bxs-x-circle bx-md' style="color: black"></i></button>
                    </form>
                </div>
            </div>
            <hr class="mt-2">
        @endforeach

        @if(count($senders) == 0)
        <h4 class="text-dark text-center">Geen match gevonden :( </h4>
        @endif
     </div>
</div>
@endsection
