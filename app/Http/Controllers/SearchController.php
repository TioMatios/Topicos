<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\User;
use App\Models\Community;
use Illuminate\Http\Request;

class SearchController extends Controller
{
    public function index(Request $request)
    {
        $query = $request->input('q');

        if (!$query) {
            return redirect()->route('dashboard');
        }

        $users = User::where('name', 'LIKE', "%{$query}%")->take(5)->get();
        $posts = Post::where('title', 'LIKE', "%{$query}%")->orWhere('content', 'LIKE', "%{$query}%")->take(10)->get();
        $communities = Community::where('name', 'LIKE', "%{$query}%")->take(5)->get();

        return view('search.results', [
            'query' => $query,
            'users' => $users,
            'posts' => $posts,
            'communities' => $communities,
        ]);
    }
}