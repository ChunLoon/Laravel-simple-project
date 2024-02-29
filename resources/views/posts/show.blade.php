@extends('layouts.app')    <!--  use this layout header body  -->

@section('content')
                                   
       
<a href="/posts" class="btn btn-default">Go Back</a>
<h1>{{$post->title}}</h1>            <!--  title  -->  
<img style="width:100%" src="/storage/cover_images/{{$post->cover_image}}">
<br><br>    
<div>
    {!!$post->body!!}
</div>
<hr>
<small>Written on {{$post->created_at}}  </small>
<hr>
@if(!Auth::guest()) <!-- if the guest can see blog but have not edit delete button-->
        @if(Auth::user()->id == $post->user_id)  <!-- only whocreate  only can edit and delete-->
<a href="/posts/{{$post->id}}/edit" class="btn btn-default">Edit</a>   <!--edit button-->

{!!Form::open(['action' => ['App\Http\Controllers\PostsController@destroy', $post->id], 'method' => 'POST', 'class' => 'pull-right'])!!}
{{Form::hidden('_method', 'DELETE')}}
{{Form::submit('Delete', ['class' => 'btn btn-danger'])}}
{!!Form::close()!!}
@endif
    @endif
@endsection