@extends('layouts.app')

@section('title', 'Home')

@section('content')

         @if(session()->has('message'))
                    <div class="m-0 text-center alert alert-danger alert-dismissible fade show w-50 mx-auto" role="alert">
                        {{ session()->get('message') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
         @endif

<div class="layout-margin-8 w-100 col-sm-6">
    <div class="row">
        @if($user)
        <div class="col-lg-4 pb-4 mx-auto" data-role="recipe">
            <div class="card h-100">

                @if(empty($user->user_images[0]->folder) || empty($user->user_images[0]->image))
                @php $user_image = 'avatar.jpg' @endphp
                @else
                @php $user_image = $user->user_images[0]->folder.'/'.$user->user_images[0]->image @endphp
                @endif
                <img height="250" class="card-img-top" title="" src="{{ asset('upload/'.$user_image)}}" alt="card-img">

                <div class="card-body">
                    <h5 class="card-title text-center">{{ ucfirst($user->user_info->first_name) . ' ' . ucfirst($user->user_info->last_name) }}</h5>
                    <hr>
                    <p class="card-text">
                        @php
                            $age = App\Http\Controllers\HomeController::getAge($user->user_info->birthday);
                        @endphp
                        <span><b>Leeftijd:</b> {{  $age  }}</span>
                    </p>
                    <p class="card-text">
                        <span><b>Woonplaats:</b> {{ ucfirst($user->user_info->city) }}</span>
                        <br>
                        <span><b>Geslacht:</b>
                            @if($user->user_info->gender == 'women')
                            Vrouw
                            @else
                            {{ ucfirst($user->user_info->gender) }}
                            @endif
                        </span>
                    </p>
                    <p class="card-text" id="desc">
                        <span><b>Beschrijven: </b> <br><p id="desc_info">{{ $user->user_info->bio }}</p> </span>
                    </p>
                    <div class="card-title">
                        <span><b>Tags:</b></span>
                        <ul class="list-group list-group-horizontal-sm justify-content-center">
                            @foreach ($user_tags as $tag)
                             <li class="list-group-item bg-secondary opacity-75 text-white rounded m-1 p-1"> {{ $tag }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
                <div class="card-footer text-muted text-truncate text-center d-flex justify-content-around">
                    <form action="{{ route('like', $user->id) }}" method="post">
                        @csrf
                        <button type="submit" class="border-0 bg-transparent"><i class='bx bxs-heart bx-md text-danger'></i></button>
                    </form>

                    <form action="{{ route('dislike', $user->id) }}" method="post">
                        @csrf

                        <button type="submit" class="border-0 bg-transparent"><i class='bx bxs-x-circle bx-md' style="color: black"></i></button>
                    </form>

                </div>
            </div>
        </div>
        @else
        <h4 class="text-light text-center mt-5">Geen match gevonden probeer je voorkeuren te wijzigen</h4>
        @endif
    </div>
</div>


@endsection
