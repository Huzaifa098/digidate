@extends('layouts.dashboard')

@section('title', 'Tags')


@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="d-flex flex-row-reverse">
                <button class="btn btn-md btn-dark">
                    <a href="{{ route('tags.create') }}" class="p-2 text-decoration-none text-success"><b>+</b> Tag toevoegen </a>
                </button>
            </div>
            <br>
            <div class="card">
                <div class="card-body">
                    @if (count($user_tags['tags']))
                    <table class="table table-responsive">
                        <thead class="text-dark">
                        <tr>
                            <th>Naam</th>
                            <th>Gemaakt op</th>
                            <th>Laatste update</th>
                            <th>Gemaakt door</th>
                            <th></th>
                            <th></th>
                        </tr>
                        </thead>
                        <tbody>

                        @foreach($user_tags['tags'] as $tag)
                            <tr>
                                <td>{{ $tag['name'] }}</td>
                                <td>{{ $tag['created_at']}}</td>
                                <td>{{ $tag['updated_at']}}</td>
                                <td>{{ $user_tags['user_info']['first_name'] }}</td>
                                <td> <a href="{{ route('tags.edit', $tag['id']) }}" type="button" class="btn btn-secondary">Update</a> </td>
                                <td>
                                    <form action="{{ route('tags.destroy', $tag['id']) }}" method="POST">
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
                    <p class="text-center text-secondary">Er zijn geen tags gevonden</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection


