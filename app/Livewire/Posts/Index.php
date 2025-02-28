<?php

namespace App\Livewire\Posts;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Post;

class Index extends Component
{
    use WithPagination;

    public $postTitle, $postContent, $postId;
    public $isOpenCreate = false;
    public $isOpenEdit = false;

    public $rowPerPage = 10;
    public $search;

    public function render()
    {
        return view('livewire.posts.index',[
            'posts'=>$this->search === null ?
                Post::latest()->paginate($this->rowPerPage) :
                Post::latest()->where('title','like','%'.$this->search.'%')->paginate($this->rowPerPage),
        ]);
    }

    public function postCreate(){
        $this->resetInputFields();
        $this->openModalCreate();
    }

    public function resetInputFields(){
        $this->postTitle = '';
        $this->postContent = '';
        $this->postId = '';
    }

    public function openModalCreate(){
        $this->isOpenCreate = true;
    }

    public function closeModalCreate(){
        $this->isOpenCreate = false;
    }

    public function postStore(){
        $this->validate([
            'postTitle' => 'required',
            'postContent' => 'required',
        ]
    );

    Post::updateOrCreate(['id' => $this->postId], [
        'title' => $this->postTitle,
        'content' => $this->postContent
    ]);

    session()->flash('message', $this->postId ? 'Post Updated Successfully.' : 'Post Created Successfully.');

    $this->closeModalCreate();
    $this->closeModalEdit();
    $this->resetInputFields();
    }

    public function postEdit($id){
        $post = Post::findOrFail($id);

        $this->postId = $id;
        $this->postTitle = $post->title;
        $this->postContent = $post->content;

        $this->openModalEdit();
    }

    public function openModalEdit(){
        $this->isOpenEdit = true;
    }

    public function closeModalEdit(){
        $this->isOpenEdit = false;
    }

    public function postDelete($id){
        Post::find($id)->delete();
        session()->flash('message', 'Post Deleted Successfully.');
    }
}
