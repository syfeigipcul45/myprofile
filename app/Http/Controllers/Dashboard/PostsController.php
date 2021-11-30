<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\PostCategories;
use App\Models\Posts;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;

class PostsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data['posts'] = Posts::latest()->get();
        return view('dashboard.posts.index', $data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $data['post_category_id'] = PostCategories::orderBy('name')->get();
        return view('dashboard.posts.create', $data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'title' => 'required',
                'content' => 'required',
                'media' => 'required',
                'category_id' => 'required',
            ], [
                'title.required' => 'Judul berita harus diisi!',
                'content.required' => 'Konten berita harus diisi!',
                'media.required' => 'Thumbnail berita harus diisi!',
                'category_id.required' => 'Kategori berita harus diisi!'
            ]);

            if ($validator->fails()) {
                return redirect()->back()->withInput()->withErrors($validator);
            }

            $data = [
                "author_id" => Auth::user()->id,
                "title" => $request->title,
                "slug" => Str::slug($request->title, '-'),
                "content" => $request->content,
                "post_category_id" => $request->category_id,
            ];

            $post = Posts::create($data);

            if ($request->hasFile('media') && $request->file('media')->isValid()) {
                $post->addMediaFromRequest('media')->toMediaCollection('posts');
            }
            // $post->addFromMediaLibraryRequest($request->media)
            //     ->toMediaCollection('posts');
            Session::flash('success', 'Data Berhasil Tersimpan');

            return redirect()->route('dashboard.posts.index');
        } catch (\Exception $exception) {
            return redirect()->back()->with('error', 'Ada sesuatu yang salah di server!');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
