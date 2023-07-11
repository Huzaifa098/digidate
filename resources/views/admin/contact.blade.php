@extends('layouts.dashboard')

@section('title', 'Berichten')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body">
                        @if ($messages->count())
                        <table class="table table-responsive">
                            <thead>
                            <tr>
                                <th>Naam</th>
                                <th>subject</th>
                                <th>message</th>
                                <th>Email</th>
                                <th>Datum</th>
                                <th></th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($messages as $message)
                                <tr>
                                    <td>{{ $message->name}}</td>
                                    <td>{{ $message->subject}}</td>
                                    <td>{{ $message->message}}</td>
                                    <td>{{ $message->email}}</td>
                                    <td>{{ $message->created_at}}</td>
                                    <td>
                                        <form action="{{ route('contact.destroy', $message->id) }}" method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger">Verwijderen</button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                        @else
                        <p class="text-center text-secondary">Er zijn geen berichten</p>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
