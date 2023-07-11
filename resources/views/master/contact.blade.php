@extends('layouts.app')

@section('title', 'Contact')


@section('content')

<div class="container w-50 mx-auto bg-light p-0 contact-page rounded">
    @if(session()->has('message'))
            <div class="m-0 text-center alert alert-warning fade show" role="alert">
                {{ session()->get('message') }}
            </div>
    @endif

    <form action="{{ route('contact.store') }}" class="p-5" method="POST">
        @csrf
        <div>
            <div class="form-group">
                <label for="name">Naam</label>
                <input type="name" class="form-control" id="name" name="name"
                @auth
                    value="{{ $full_name }}"
                @endauth
                >
            </div>
            <br />
            <div class="form-group">
                <label for="subject">Subject</label>
                <input type="text" class="form-control" id="subject" name="subject">
            </div>
            <br />
            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" class="form-control" id="email" name="email"
                @auth
                    value=" {{$email }}"
                @endauth
                >
            </div>
            <br />
            <div class="form-group">
                <label for="message">Bericht</label>
                <textarea class="form-control" id="message" name="message" rows="10" cols="30"></textarea>
                <small class="d-flex flex-row-reverse">Max 255</small>
            </div>
            <br />

            <button class="btn btn-dark mt-5 w-100" type="submit">Verzend</button>
        </div>
    </form>
</div>

@endsection
