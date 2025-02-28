<div>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    @if (session()->has('message'))
    <div class="bg-teal-100 border-t-4 border-teal-500 rounded-b text-teal-900 px-4 py-3 shadow-md my-3" role="alert">
        <div class="flex">
            <div>
                <p class="text-sm">{{ session('message') }}</p>
            </div>
        </div>
    </div>
    @endif

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                {{--Button div new post--}}
                <div wire:click="postCreate()" class="flex justify-start cursor-pointer px-4 py-3">
                    <a class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                        Create a New Post
                    </a>
                </div>

                @if($isOpenCreate)
                @include('livewire.posts.create')
                @endif

                @if($isOpenEdit)
                @include('livewire.posts.edit')
                @endif

                {{--Gropu of Searchbar and Filter--}}
                <div class="flex justify-between gap-4 flex-row-reverse px-4 py-3">
                    <div class="w-2/3">
                        <label for="search" class="text-lg font-bold mr-4">Search:</label>
                        <input wire:model.live="search" type="text" class=" border-2 border-gray-300 p-2 rounded-lg" placeholder="Search...">
                    </div>
                    <div class="w-1/3 flex
                    justify-start items-baseline">
                        <label for="filter" class=" text-lg font-bold mr-4">Row per page:</label>
                        <select wire:model.live="rowPerPage" class="border-2 w-32 border-gray-300 p-2 rounded-lg">
                            <option value="5">5</option>
                            <option value="10">10</option>
                            <option value="20">20</option>
                        </select>
                    </div>
                </div>
                {{--Table--}}


                <div class="relative overflow-x-auto">
                    <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
                        <thead class="text-xs uppercase bg-white">
                            <tr>
                                <th scope="col" class="px-6 py-3">
                                    Title
                                </th>
                                <th scope="col" class="px-6 py-3">
                                    Content
                                </th>
                                <th scope="col" class="px-6 py-3">
                                    Action
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($posts as $key => $post)
                            <tr class="bg-white border-b border-gray-200">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div  class="whitespace-nowrap">{{ $post->title }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-wrap">{{ $post->content }}</div>
                                </td>
                                <td class="flex flex-row px-6 py-4 whitespace-nowrap">
                                    <button class="mr-2 bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                                        Show
                                    </button><button wire:click="postEdit({{$post->id}})" class="mr-2 bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">
                                        Edit
                                    </button><button wire:click="postDelete({{$post->id}})" class="mr-2 bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded">
                                        Delete
                                    </button>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="p-4">
                    {{ $posts->links() }}
                </div>
            </div>
        </div>
    </div>