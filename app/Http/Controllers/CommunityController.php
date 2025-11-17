<?php

namespace App\Http\Controllers;

use App\Models\Community;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str; 

class CommunityController extends Controller
{
    public function index()
    {
        $communities = Community::latest()->paginate(15);
        return view('communities.index', compact('communities'));
    }

    public function create()
    {
        $this->authorize('create', Community::class);
        return view('communities.create');
    }

    public function store(Request $request)
    {
        $this->authorize('create', Community::class);

        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:communities',
            'description' => 'required|string|max:1000',
            'icon' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);
        
        $iconPath = null;
        if ($request->hasFile('icon')) {
            $iconPath = $request->file('icon')->store('community-icons', 'public');
        }

        Community::create([
            'user_id' => Auth::id(),
            'name' => $validated['name'],
            'slug' => Str::slug($validated['name']), 
            'description' => $validated['description'],
            'icon_url' => $iconPath,
        ]);

        return redirect()->route('communities.index')->with('success', 'Comunidade criada com sucesso!');
    }

    public function show(Community $community)
{
    $posts = $community->posts()
                       ->with(['user', 'comments', 'likedByUsers'])
                       ->orderByRaw('pinned_at IS NULL')
                       ->orderBy('pinned_at', 'desc')
                       ->latest()
                       ->paginate(10);
                       
    return view('communities.show', compact('community', 'posts'));
}

    public function edit(Community $community)
    {
        $this->authorize('update', $community);
        return view('communities.edit', compact('community'));
    }

    public function update(Request $request, Community $community)
    {
        $this->authorize('update', $community);

        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:communities,name,' . $community->id,
            'description' => 'required|string|max:1000',
            'icon' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);
        
        $iconPath = $community->icon_url;
        if ($request->hasFile('icon')) {
            $iconPath = $request->file('icon')->store('community-icons', 'public');
        }

        $community->update([
            'name' => $validated['name'],
            'slug' => Str::slug($validated['name']), 
            'description' => $validated['description'],
            'icon_url' => $iconPath,
        ]);

        return redirect()->route('communities.show', $community)->with('success', 'Comunidade atualizada!');
    }

    public function destroy(Community $community)
    {
        $this->authorize('delete', $community);
        $community->delete();
        return redirect()->route('communities.index')->with('success', 'Comunidade excluída!');
    }

    public function toggleJoin(Community $community)
    {
        Auth::user()->joinedCommunities()->toggle($community->id);
        return back();
    }
}