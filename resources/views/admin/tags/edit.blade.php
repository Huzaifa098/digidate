
@extends('layouts.dashboard')

@section('title', 'Tags: Bewerken')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8 mx-auto">
            <div class="card">
                <div class="card-header">Tag bewerken</div>
                <div class="card-body">
                    <form action="{{ route('tags.update' , $tag->id) }}" method="POST">
                        @csrf
                        @method('put')
                        <div class="form-group">
                            <label for="name">Naam:</label>
                            <div class="d-flex justify-content-around">
                                <input type="name" class="form-control m-1" id="name" name="name" value="{{ $tag->name }}">
                                <span class="text-danger">{{ $errors->first('name') }}</span>
                            </div>
                        </div>
                        <br>
                        <button type="submit" class="btn btn-primary">Updaten</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection


