@extends('layouts.app') 

@section('content')
<div class="container mx-auto p-4 max-w-4xl">
    <h1 class="text-3xl font-bold mb-6">ایجاد پست جدید</h1>

    <form method="POST" action="{{ route('posts.store') }}" enctype="multipart/form-data" class="bg-white p-6 rounded-lg shadow-lg">
        @csrf

        {{-- عنوان پست --}}
        <div class="mb-4">
            <label for="title" class="block text-gray-700 text-sm font-bold mb-2">عنوان:</label>
            <input type="text" id="title" name="title" value="{{ old('title') }}" required 
                   class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
            @error('title') <p class="text-red-500 text-xs italic">{{ $message }}</p> @enderror
        </div>

        {{-- محتوای پست --}}
        <div class="mb-4">
            <label for="content" class="block text-gray-700 text-sm font-bold mb-2">محتوا:</label>
            <textarea id="content" name="content" rows="10" required 
                      class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">{{ old('content') }}</textarea>
            @error('content') <p class="text-red-500 text-xs italic">{{ $message }}</p> @enderror
        </div>
        
        {{-- تصویر شاخص --}}
        <div class="mb-4">
            <label for="thumbnail" class="block text-gray-700 text-sm font-bold mb-2">تصویر شاخص:</label>
            <input type="file" id="thumbnail" name="thumbnail" 
                   class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
            @error('thumbnail') <p class="text-red-500 text-xs italic">{{ $message }}</p> @enderror
        </div>

        {{-- برچسب‌ها (Tags) --}}
        <div class="mb-4">
            <label for="tag_ids" class="block text-gray-700 text-sm font-bold mb-2">برچسب‌ها:</label>
            <select name="tag_ids[]" id="tag_ids" multiple 
                    class="shadow border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline h-32">
                @foreach($tags as $tag)
                    <option value="{{ $tag->id }}" {{ in_array($tag->id, old('tag_ids', [])) ? 'selected' : '' }}>
                        {{ $tag->name }}
                    </option>
                @endforeach
            </select>
            @error('tag_ids') <p class="text-red-500 text-xs italic">{{ $message }}</p> @enderror
        </div>


        {{-- دکمه‌های ارسال --}}
        <div class="flex items-center justify-between">
            <button type="submit" name="publish" 
                    class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                انتشار
            </button>
            <button type="submit" name="draft" 
                    class="bg-yellow-500 hover:bg-yellow-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                ذخیره پیش‌نویس
            </button>
        </div>
    </form>
</div>
@endsection