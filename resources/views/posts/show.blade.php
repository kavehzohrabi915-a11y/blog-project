@extends('layouts.app')

@section('content')
<div class="container mx-auto p-4 max-w-4xl">
    <article class="bg-white p-8 rounded-lg shadow-xl">
        {{-- عنوان و اطلاعات پست --}}
        <h1 class="text-4xl font-extrabold mb-4 text-gray-900">{{ $post->title }}</h1>
        
        <p class="text-sm text-gray-500 mb-6 border-b pb-4">
            نویسنده: <span class="font-semibold">{{ $post->user->name ?? 'ناشناس' }}</span> | 
            تاریخ انتشار: {{ $post->published_at ? $post->published_at->format('Y/m/d') : 'پیش نویس' }}
        </p>

        {{-- تصویر شاخص --}}
        @if($post->thumbnail)
            <img src="{{ asset('storage/' . $post->thumbnail) }}" alt="{{ $post->title }}" class="w-full h-auto rounded-lg mb-6 shadow-md">
        @endif
        
        {{-- محتوای اصلی پست --}}
        <div class="prose max-w-none text-gray-800 leading-relaxed text-lg">
            {{-- در محیط واقعی، برای نمایش محتوای غنی (Rich Content) باید از {!! $post->content !!} استفاده شود. --}}
            <p>{{ $post->content }}</p>
        </div>
        
        {{-- برچسب‌ها --}}
        @if($post->tags->count())
            <div class="mt-8 pt-4 border-t">
                <span class="font-semibold text-gray-700">برچسب‌ها:</span>
                @foreach($post->tags as $tag)
                    <span class="inline-block bg-blue-100 text-blue-800 text-xs px-3 py-1 rounded-full ml-2">
                        {{ $tag->name }}
                    </span>
                @endforeach
            </div>
        @endif
    </article>

    {{-- بخش نظرات --}}
    <section class="mt-12">
        <h2 class="text-2xl font-bold mb-4 border-b pb-2">نظرات ({{ $post->comments->count() }})</h2>

        @forelse ($post->comments as $comment)
            <div class="bg-gray-50 p-4 rounded-lg mb-4 border-l-4 border-blue-500">
                <p class="font-semibold text-sm">{{ $comment->author_name ?? $comment->user->name }}</p>
                <p class="text-gray-700 mt-1">{{ $comment->content }}</p>
                <p class="text-xs text-gray-400 mt-2">{{ $comment->created_at->diffForHumans() }}</p>
            </div>
        @empty
            <p class="text-gray-500">اولین نظر را شما ثبت کنید.</p>
        @endforelse

        {{-- فرم ثبت نظر (این نیاز به CommentController دارد) --}}
        <div class="mt-8 p-6 bg-white rounded-lg shadow-md">
            <h3 class="text-xl font-semibold mb-4">ارسال نظر</h3>
            {{-- فرم ثبت نظر در اینجا قرار می‌گیرد --}}
            {{-- برای تکمیل این بخش باید CommentController و Route مربوطه را بسازید. --}}
        </div>
    </section>
</div>
@endsection