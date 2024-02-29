@extends('layouts.app')    <!--  use this layout header body  -->

@section('content')
    <div class="jumbotron text-center">
        <h1>{{ $title }}</h1> 
        <p>This is the Laravel application from the "Laravel From Scratch" YouTube series</p>
        <p><a class="btn btn-primary btn-lg" href="/login" role="button">Login</a>
     <!-- <a>= create hyperlinks ,class=button ,href=url,role what type-->
            <a class="btn btn-success btn-lg" href="/register" role="button">Register</a></p>

    </div>
@endsection
