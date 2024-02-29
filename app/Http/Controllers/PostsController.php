<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Post;      // file location will use  for model
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;

use DB;

class PostsController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth', ['except' => ['index', 'show']]);    //block user who are not auth can see the post but cannot create need to login first
    }

    /**
     * Display a listing of the resource first page
     */
    public function index()
    {
        //return Post::all();          //can see the data is database Post One Two ,add app\models\post because use that location
         //$posts = Post::all();       //can see the data is database Post One Two ,add app\models\post because use that location
        //return Post::where('title', 'Post Two')->get();
        //$posts = DB::select('SELECT * FROM posts');
        //$posts = Post::orderBy('title','desc')->take(1)->get(); //display one data
        //$posts = Post::orderBy('title','desc')->get();
        $posts = Post::orderBy('created_at','desc')->paginate(10);  // Post table data 10 data in one [age]
        return view('posts.index')->with('posts', $posts); //location file , posts variable,posts data,
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('posts.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'title' => 'required',
            'body' => 'required',
            'cover_image' => 'image|nullable|max:1999'

        ]);


 // Handle File Upload
 if($request->hasFile('cover_image')){
    // Get filename with the extension
    $filenameWithExt = $request->file('cover_image')->getClientOriginalName();
    // Get just filename
    $filename = pathinfo($filenameWithExt, PATHINFO_FILENAME);
    // Get just ext
    $extension = $request->file('cover_image')->getClientOriginalExtension();
    // Filename to store
    $fileNameToStore= $filename.'_'.time().'.'.$extension;
    // Upload Image
    $path = $request->file('cover_image')->storeAs('public/cover_images', $fileNameToStore);

// make thumbnails
    $thumbStore = 'thumb.'.$filename.'_'.time().'.'.$extension;       //add immage use
    $thumb = Image::make($request->file('cover_image')->getRealPath());
    $thumb->resize(80, 80);
    $thumb->save('storage/cover_images/'.$thumbStore);

} else {
    $fileNameToStore = 'noimage.jpg';
}

        $post = new Post;                                     //save to database
        $post->title = $request->input('title');
        $post->body = $request->input('body');
        $post->user_id = auth()->user()->id;
        $post->cover_image = $fileNameToStore;
        $post->save();
        //return 123; //if save  will display
        return redirect('/posts')->with('success', 'Post Created');       //link redirect to url
    }

    /**
     * Display the specified resource to interface
     */
    public function show(string $id)         
    {  
        //return Post::find($id);       //can see the data is database Post One Two by id ,add app\models\post because use that location
        $post = Post::find($id);  
        return view('posts.show')->with('post', $post); //location file , post variable,post data,
    }
    

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $post = Post::find($id);
        
        //Check if post exists before deleting
        if (!isset($post)){
            return redirect('/posts')->with('error', 'No Post Found');
        }

        // Check for correct user   only that user can edit with this url
       if(auth()->user()->id !==$post->user_id){
            return redirect('/posts')->with('error', 'Unauthorized Page');
        }

        return view('posts.edit')->with('post', $post);
    }
    

    /**
     * Update the specified resource in storage and save in database
     */
    public function update(Request $request, string $id)
    {
        $this->validate($request, [
            'title' => 'required',
            'body' => 'required'
        ]);
        $post = Post::find($id);
        if($request->hasFile('cover_image')){
            // Get filename with the extension
            $filenameWithExt = $request->file('cover_image')->getClientOriginalName();
            // Get just filename
            $filename = pathinfo($filenameWithExt, PATHINFO_FILENAME);
            // Get just ext
            $extension = $request->file('cover_image')->getClientOriginalExtension();
            // Filename to store
            $fileNameToStore= $filename.'_'.time().'.'.$extension;
            // Upload Image
            $path = $request->file('cover_image')->storeAs('public/cover_images', $fileNameToStore);
            // Delete file if exists
            Storage::delete('public/cover_images/'.$post->cover_image);
		
	   //Make thumbnails
	    $thumbStore = 'thumb.'.$filename.'_'.time().'.'.$extension;
            $thumb = Image::make($request->file('cover_image')->getRealPath());
            $thumb->resize(80, 80);
            $thumb->save('storage/cover_images/'.$thumbStore);
		
        }
        $post->title = $request->input('title');
        $post->body = $request->input('body');
        if($request->hasFile('cover_image')){
            $post->cover_image = $fileNameToStore;
        }
        $post->save();
        return redirect('/posts')->with('success', 'Post Updated');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $post = Post::find($id);
        if (!isset($post)){
            return redirect('/posts')->with('error', 'No Post Found');
        }

        // Check for correct user
        if(auth()->user()->id !==$post->user_id){
            return redirect('/posts')->with('error', 'Unauthorized Page');
        }

        if($post->cover_image != 'noimage.jpg'){
            // Delete Image
            Storage::delete('public/cover_images/'.$post->cover_image);
        }
        
        $post->delete();

        return redirect('/posts')->with('success', 'Post Removed');
    }
}
