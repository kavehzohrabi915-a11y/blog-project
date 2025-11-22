@extends('layouts.app')

@section('content')
<div class="container mx-auto p-4 max-w-4xl">
    <h1 class="text-3xl font-bold mb-6">ویرایش پست: {{ $post->title }}</h1>
    
    <form method="POST" action="{{ route('posts.update', $post->slug) }}" enctype="multipart/form-data" class="bg-white p-6 rounded-lg shadow-lg">
        @csrf
        @method('PATCH') {{-- استفاده از متد PATCH برای به‌روزرسانی --}}

        {{-- عنوان پست --}}
        <div class="mb-4">
            <label for="title" class="block text-gray-700 text-sm font-bold mb-2">عنوان:</label>
            <input type="text" id="title" name="title" value="{{ old('title', $post->title) }}" required 
                   class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
            @error('title') <p class="text-red-500 text-xs italic">{{ $message }}</p> @enderror
        </div>

        {{-- محتوای پست --}}
        <div class="mb-4">
            <label for="content" class="block text-gray-700 text-sm font-bold mb-2">محتوا:</label>
            <textarea id="content" name="content" rows="10" required 
                      class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">{{ old('content', $post->content) }}</textarea>
            @error('content') <p class="text-red-500 text-xs italic">{{ $message }}</p> @enderror
        </div>
        
        {{-- تصویر شاخص فعلی و فیلد جدید --}}
        @if($post->thumbnail)
            <div class="mb-4">
                <p class="text-sm font-semibold mb-2">تصویر فعلی:</p>
                <img src="{{ asset('storage/' . $post->thumbnail) }}" alt="Thumbnail" class="w-32 h-auto mb-2">
            </div>
        @endif
        <div class="mb-4">
            <label for="thumbnail" class="block text-gray-700 text-sm font-bold mb-2">تصویر شاخص جدید:</label>
            <input type="file" id="thumbnail" name="thumbnail" 
                   class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
        </div>

        {{-- برچسب‌ها (Tags) --}}
        <div class="mb-4">
            <label for="tag_ids" class="block text-gray-700 text-sm font-bold mb-2">برچسب‌ها:</label>
            <select name="tag_ids[]" id="tag_ids" multiple 
                    class="shadow border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline h-32">
                @php
                    $currentTags = old('tag_ids', $post->tags->pluck('id')->toArray());
                @endphp
                @foreach($tags as $tag)
                    <option value="{{ $tag->id }}" {{ in_array($tag->id, $currentTags) ? 'selected' : '' }}>
                        {{ $tag->name }}
                    </option>
                @endforeach
            </select>
        </div>


        {{-- دکمه‌های ارسال --}}
        <div class="flex items-center justify-between">
            <button type="submit" name="publish" 
                    class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                {{ $post->published_at ? 'به‌روزرسانی و انتشار' : 'انتشار' }}
            </button>
            <button type="submit" name="draft" 
                    class="bg-yellow-500 hover:bg-yellow-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                ذخیره پیش‌نویس
            </button>
        </div>
    </form>
    
    {{-- فرم حذف (Delete) --}}
    <form method="POST" action="{{ route('posts.destroy', $post->slug) }}" onsubmit="return confirm('آیا مطمئن هستید؟')" class="mt-4">
        @csrf
        @method('DELETE')
        <button type="submit" class="text-red-500 hover:text-red-700 text-sm">
            حذف پست
        </button>
    </form>
</div>
@endsection