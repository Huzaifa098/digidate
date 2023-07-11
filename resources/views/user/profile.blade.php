@extends('layouts.app')

@section('title', 'Setting')

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
        <div class="col-4 pe-0">
            <button class="btn btn-dark btn-lg w-100 rounded-0">Instelling</button>
          <div class="list-group rounded-0" id="list-tab" role="tablist">
            <a class="list-group-item list-group-item-action active" id="list-profile-list" data-bs-toggle="list" href="#list-profile" role="tab" aria-controls="list-profile">Profile</a>
            <a class="list-group-item list-group-item-action" id="list-profile-info-list" data-bs-toggle="list" href="#list-profile-info" role="tab" aria-controls="list-profile-info">Persoonlijk gegevens</a>
            @if($user->role == 'user' || $user->role == 'adminUser')
            <a class="list-group-item list-group-item-action" id="list-pref-list" data-bs-toggle="list" href="#list-pref" role="tab" aria-controls="list-pref">Voorkeuren</a>
            @endif
            <a class="list-group-item list-group-item-action" id="list-password-list" data-bs-toggle="list" href="#list-password" role="tab" aria-controls="list-password">Email en wachtwoord</a>
        </div>
        </div>
        <div class="col-8 ps-1">
            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
             @endif
          <div class="tab-content rounded-0" id="nav-tabContent">
            <div class="tab-pane fade show active" id="list-profile" role="tabpanel" aria-labelledby="list-profile-list">
                <form action="{{ route('update.profile') }}" class="p-5 border border-dark" method="post" enctype="multipart/form-data">
                    @csrf
                    @method('put')
                    <div class="text-center  mb-3">
                        @if($user_image == 'notFound')
                        @php $user_image = 'avatar.jpg' @endphp
                        @endif
                        <img class="rounded-circle border border-dark" width="200" height="200" src="{{ asset('upload/'. $user_image)}}" alt="primary-foto">

                    </div>

                    <div class="form-group">
                        <label for="image">Profile foto</label>
                        <input type="file" class="form-control" id="image" name="image">
                    </div>
                    <br />
                    <div class="form-group">
                        <label for="bio">Beschrijven</label>
                        <textarea class="form-control" id="bio" name="bio" rows="10" cols="30" placeholder="Beschrijf je zelf...">{{ $user->user_info->bio }}</textarea>
                        <small class="d-flex flex-row-reverse">Max 255</small>
                    </div>
                    <br />
                        <h4>Galerij:</h4>
                        <br>
                        <div class="row">
                            @foreach($normal_images as $image)
                            <div class="col-md border p-2 text-center">
                                <img class="text-center rounded border border-dark" width="200" height="200" src="{{ asset('upload/'. $image->folder.'/'.$image->image)}}" alt="profile-image">

                                <div class="d-flex justify-content-center mt-2 mx-auto">
                                    @if($image->status == 'normal')
                                    <a href="{{ route('setImageToPrimary', $image->id)  }}" type="button" class="btn btn-success m-1">Als Profile foto</a>
                                    @endif
                                    <a href="{{ route('delete.images', $image->id) }}" type="button" class="btn btn-danger m-1">Verwijderen</a>
                                </div>
                                <br>
                            </div>
                            @endforeach
                        </div>
                        <button class="btn btn-dark mt-5 w-100" type="submit">Update</button>
                </form>
            </div>
            <div class="tab-pane fade" id="list-profile-info" role="tabpanel" aria-labelledby="list-profile-info-list">
                <form action="{{ route('update.personal') }}" class="p-5 border border-dark" method="post">
                    @csrf
                    @method('put')
                    <div>
                        <div class="form-group">
                            <label for="name">Naam</label>
                            <div class="d-flex flex-lg-row flex-sm-column">
                                <input type="name" class="form-control m-1" id="name" name="first_name" placeholder="voornaam" value={{ $user->user_info->first_name }}>
                                <input type="name" class="form-control m-1" id="name" name="middle_name" placeholder="tussenvoegsel" value={{ $user->user_info->middle_name }}>
                                <input type="name" class="form-control m-1" id="name" name="last_name" placeholder="achternaam" value={{ $user->user_info->last_name }}>
                            </div>
                        </div>
                        <br />
                        <div class="form-group">
                            <label for="city">Woonplaats</label>
                            <select class="form-control" id="city" name="city">
                                <option value="" selected disabled>Selecteer...</option>
                                <option value="amsterdam" @if ($user->user_info->city == "amsterdam") {{ 'selected' }} @endif>Amsterdam</option>
                                <option value="rotterdam" @if ($user->user_info->city == "rotterdam") {{ 'selected' }} @endif>Rotterdam</option>
                                <option value="den Haag" @if ($user->user_info->city == "den Haag") {{ 'selected' }} @endif>Den Haag</option>
                                <option value="utrecht" @if ($user->user_info->city == "utrecht") {{ 'selected' }} @endif>Utrecht</option>
                                <option value="eindhoven" @if ($user->user_info->city == "eindhoven") {{ 'selected' }} @endif>Eindhoven</option>
                                <option value="groningen" @if ($user->user_info->city == "groningen") {{ 'selected' }} @endif>Groningen</option>
                                <option value="tilburg" @if ($user->user_info->city == "tilburg") {{ 'selected' }} @endif>Tilburg</option>
                            </select>
                        </div>

                        <br />
                        <div class="form-group">
                            <label for="phone">Telefoonnummer</label>
                            <input type="phone" class="form-control" id="phone" name="phone" value={{ 0 . $user->user_info->phone }}>
                        </div>

                        <br />
                        <div class="form-group">
                            <label for="gender">Geslacht</label>
                            <select class="form-control" id="gender" name="gender">
                                <option value="" @if ($user->user_info->gender !== "man" && $user->user_info->gender!== "women") {{ 'selected' }} @endif disabled>Selecteer je geslacht...</option>
                                <option value="man" @if ($user->user_info->gender == "man") {{ 'selected' }} @endif>Man</option>
                                <option value="women" @if ($user->user_info->gender == "women") {{ 'selected' }} @endif>Vrouw</option>
                            </select>
                        </div>

                        <br />
                        <div class="form-group">
                            <label for="birthday">Geboortedatum:</label>
                            <input type="date" class="form-control" name="birthday" id="birthday" value={{ $user->user_info->birthday }}>
                        </div>

                        <button class="btn btn-dark mt-5 w-100" type="submit">Update</button>
                        <br />
                    </div>
                  </form>
            </div>
            <div class="tab-pane fade" id="list-pref" role="tabpanel" aria-labelledby="list-pref-list">
                <form action=" {{ route('update.pref') }}" class="p-5 border border-dark" method="post">
                    @csrf
                    @method('put')
                    <div class="form-group">
                        <label for="gender">Voorkeur geslacht</label>
                        <select class="form-control" id="gender" name="gender">
                            @if ($user->user_preferences) {{  $gender = $user->user_preferences->gender }} @else {{ $gender = '' }} @endif
                            <option value="" @if ( $gender !== "man" && $gender!== "women") {{ 'selected' }} @endif disabled>Selecteer je geslacht...</option>
                                <option value="man" @if ($gender == "man") {{ 'selected' }} @endif>Man</option>
                                <option value="women" @if ($gender == "women") {{ 'selected' }} @endif>Vrouw</option>

                        </select>
                    </div>
                    <br />
                    <div class="form-group">
                        <label for="city">Voorkeur woonplaats</label>
                        <select class="form-control" id="city" name="city">
                            <option value="" selected disabled>Selecteer...</option>
                            @if ($user->user_preferences) {{  $city = $user->user_preferences->city }} @else {{ $city = '' }} @endif
                            <option value="amsterdam" @if ($city == "amsterdam") {{ 'selected' }} @endif>Amsterdam</option>
                            <option value="rotterdam" @if ($city == "rotterdam") {{ 'selected' }} @endif>Rotterdam</option>
                            <option value="den Haag" @if ($city == "den Haag") {{ 'selected' }} @endif>Den Haag</option>
                            <option value="utrecht" @if ($city == "utrecht") {{ 'selected' }} @endif>Utrecht</option>
                            <option value="eindhoven" @if ($city == "eindhoven") {{ 'selected' }} @endif>Eindhoven</option>
                            <option value="groningen" @if ($city == "groningen") {{ 'selected' }} @endif>Groningen</option>
                            <option value="tilburg" @if ($city == "tilburg") {{ 'selected' }} @endif>Tilburg</option>
                        </select>
                    </div>
                    <br />

                    @if (count($tags))
                    <div class="form-group">
                        <label><b>Voorkeur tags:</b></label>
                        <br>
                        @php $selected_tags= []; @endphp
                        @if ($user->user_tags)
                            @foreach($user_tags as $user_tag)
                              @php  $selected_tags[] = $user_tag['tag_id'] @endphp
                            @endforeach
                        @endif

                        <div class="d-flex justify-content-around">
                            @foreach ($tags as $tag)
                            <div>
                                <label for="{{$tag->id}}">{{ $tag->name }}</label>
                                <input class="m-0" type="checkbox" id="{{$tag->id}}" name="tags[]" value="{{ $tag->id }}" @if ( in_array($tag->id , $selected_tags) ) {{ 'checked' }} @endif>
                            </div>
                        @endforeach
                        </div>
                    </div>
                    <br>
                    @endif
                    <div class="d-flex flex-lg-column flex-sm-column mx-auto">
                        <label><b>Voorkeur leeftijd:</b></label>
                        <div class="form-group d-flex justify-content-between m-1">
                            <label for="min" class="form-label">Minimaal:</label>
                            <input type="range" class="form-range w-50" min="18" max="60" id="min" name="age_min" oninput="this.nextElementSibling.value = this.value" value="{{ $user->user_preferences->age_min ?? $age_min = 18 }}">
                            <output class="m-0 p-0">{{ $user->user_preferences->age_min ?? $age_min = 18 }}</output>
                        </div>

                        <div class="form-group d-flex justify-content-between m-1">
                            <label for="max" class="form-label">Maximaal:</label>
                            <input type="range" class="form-range w-50" min="18" max="60" id="max" name="age_max" oninput="this.nextElementSibling.value = this.value" value="{{ $user->user_preferences->age_max ?? $age_max = 60 }}">
                            <output class="m-0 p-0">{{ $user->user_preferences->age_max ?? $age_max = 60 }}</output>
                        </div>
                    </div>
                    <br />
                    <button class="btn btn-dark mt-5 w-100" type="submit">Update</button>
                </form>
            </div>
            <div class="tab-pane fade p-5 border border-dark" id="list-password" role="tabpanel" aria-labelledby="list-password-list">
                    <div class="col-8">
                        <p>Email:</p>
                        <p>{{ ' '.$user->email }}</p>
                    </div>
                    <a href="{{ route('reset.email.form') }}"> <input type="submit" class="btn btn-primary" value="Email wijzijgen"> </a>
                <hr />
                <form action="{{ route('reset.password.post') }}" class="mt-5" method="post">
                    @csrf
                    <p>Wachtwoord:</p>
                    <div class="col-md-8">
                        <button type="submit" class="btn btn-primary">
                            Wachtwoord herstellen
                        </button>
                    </div>
                </form>
                <br /> <br /> <br /> <br /> <br />
            </div>
          </div>
        </div>
        @if($user->role == 'user')
        <form action=" {{ route('user.delete') }}" class="col-12" method="post">
            @csrf
            @method('delete')
            <button class="btn btn-danger btn-sm w-25" onclick="return confirm('Are you sure?')" type="submit">Account verwijderen</button>
        </form>
        @endif
      </div>
</div>
@endsection
