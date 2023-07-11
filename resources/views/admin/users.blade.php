@extends('layouts.dashboard')

@section('title', 'Gebruikers')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body">
                        @if ($users->count())
                        <table class="table table-responsive">
                            <thead class="text-dark">
                            <tr>
                                <th>Volledige naam</th>
                                <th>Email</th>
                                <th>Gemaakt op</th>
                                <th>Laatste update</th>
                                <th></th>
                                <th></th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($users as $userr)
                                <tr>
                                    <td>{{ $userr->user_info['first_name'] . ' ' . $userr->user_info['middle_name'] . ' ' . $userr->user_info['last_name']}}</td>
                                    <td>{{ $userr->email}}</td>
                                    <td>{{ $userr->created_at}}</td>
                                    <td>{{ $userr->updated_at}}</td>
                                    <td> <a href="{{ route('users.edit', $userr->id) }}" type="button" class="btn btn-secondary">Update</a> </td>
                                    <td>
                                        <form action="{{ route('users.destroy', $userr->id) }}" method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" onclick="return confirm('Are you sure?')" class="btn btn-danger">Verwijderen</button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                        @else
                        <p class="text-center text-secondary">Er zijn geen gebruikers</p>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
