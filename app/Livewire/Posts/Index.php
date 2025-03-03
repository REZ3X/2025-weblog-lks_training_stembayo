<?php

namespace App\Livewire\Posts;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Post;
use Livewire\WithFileUploads;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\View;

class Index extends Component
{
    use WithPagination;
    use WithFileUploads;

    public $postTitle, $postContent, $postImage, $postId;
    public $isOpenCreateOrEdit = false;

    public $rowPerPage = 10;
    public $search;

    
    public $mode = '';


    public function render()
    {
        return view('livewire.posts.index', [
            'posts' => $this->search === null ?
                Post::latest()->paginate($this->rowPerPage) :
                Post::latest()->where('title', 'like', '%' . $this->search . '%')->paginate($this->rowPerPage),
        ]);
    }

    public function postCreate()
    {
        $this->resetInputFields();
        $this->mode = 'create';
        $this->openModalCreateOrEdit();
    }

    public function resetInputFields()
    {
        $this->postTitle = '';
        $this->postContent = '';
        $this->postImage = '';
        $this->postId = '';
        $this->mode = '';
    }

    public function openModalCreateOrEdit()
    {
        $this->isOpenCreateOrEdit = true;
    }

    public function closeModalCreateOrEdit()
    {
        $this->isOpenCreateOrEdit = false;
    }

public function postStore()
{
    try {
        $validationRules = [
            'postTitle' => 'required',
            'postContent' => 'required',
        ];

        // Add image validation only if an image is being uploaded
        if ($this->postImage) {
            $validationRules['postImage'] = 'image|max:2048';
        }

        $this->validate($validationRules);

        $data = [
            'title' => $this->postTitle,
            'content' => $this->postContent,
        ];

        if ($this->postImage && !is_string($this->postImage)) {
            $imageName = time() . '.' . $this->postImage->getClientOriginalExtension();
            $this->postImage->storeAs('post_img', $imageName, 'public');
            $data['image'] = $imageName;
        }


        Post::updateOrCreate(['id' => $this->postId], 
        $data);

        session()->flash('message', $this->postId ? 'Post Updated Successfully.' : 'Post Created Successfully.');

        $this->closeModalCreateOrEdit();
        $this->resetInputFields();
    } catch (\Exception $e) {
        session()->flash('error', 'Error: ' . $e->getMessage());
    }
}
public function postEdit($id)
{
    $post = Post::findOrFail($id);
    $this->postId = $id;
    $this->postTitle = $post->title;
    $this->postContent = $post->content;
    $this->postImage = $post->image;
    $this->mode = 'edit';
    $this->openModalCreateOrEdit();
}

    public function postDelete($id)
    {
        Post::find($id)->delete();
        session()->flash('message', 'Post Deleted Successfully.');
    }

    public function postShow($id)
    {
        $post = Post::findOrFail($id);
        $this->postId = $id;
        $this->postTitle = $post->title;
        $this->postContent = $post->content;
        $this->postImage = $post->image;
        $this->mode = 'show';
        $this->openModalCreateOrEdit();
    }
}
