<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\Image;
use App\Models\Post;
use App\Models\Tag;
use App\Models\Video;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PostController extends Controller
{

    public function index()
    {
        //$posts = Post::all();
        //dd($posts);
        //update
        /* $post = Post::find(1);
         $post->update(['title'=>"New title"]);
         dd("modifié ");*/

        //delete
        /*  $post = Post::find(8);
          $post->delete();
          dd("supprimé");*/

        $posts = Post::orderByDesc('created_at')->get();
        return view('articles', compact('posts'));
    }

    public function show($id)
    {

        $post = Post::findOrFail($id);
        //$post = Post::where('title','Rem consectetur reiciens aliquid ut sit.')->firstOrFail();
        //dd($post);
        //$post = $posts[$id - 1] ?? "Pas de titre";
        return view('article', [
            'post' => $post
        ]);
    }

    public function showTag($id)
    {
        $tagName = Tag::findOrFail($id)->name;
        $posts = Tag::findOrFail($id)->posts()->get();
        return view('tags', compact('posts', 'tagName'));
    }

    public function contact()
    {
        return view('contact');
    }

    public function create()
    {
        return view('create');
    }

    public function store(Request $request)
    {

        $request->validate([
            'title' => 'required|min:5|max:255',
            'content' => ['required']
        ]);
//        Storage::disk('local')->put('avatars',$request->file('avatar'));
        $fileName = time() . '.upload.' . $request->file('avatar')->extension();
        $path = $request->file('avatar')->storeAs('avatars', $fileName, 'public');
        $image = new Image();
        $image->path = $path;
        $post = Post::create([
            'title' => $request->input('title'),
            'content' => $request->input('content'),
        ]);
        $post->image()->save($image);
        return redirect()->route('welcome');
    }

    public function register()
    {
        $comment1 = new Comment(['content' => 'First comment']);
        $comment2 = new Comment(['content' => 'Second comment']);
        $comment3 = new Comment(['content' => 'Third comment']);
        $comment4 = new Comment(['content' => 'Fourth comment']);

        $post = Post::find(12);
        $video = Video::find(1);

        $post->comments()->saveMany([
            $comment1,
            $comment2,
            $comment3,
        ]);

        $video->comments()->save($comment4);
    }
}
