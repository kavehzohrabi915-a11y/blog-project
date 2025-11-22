@extends('layouts.admin')
@section('content')
<h3>لیست پست‌ها</h3>
<a href="{{ route('admin.posts.create') }}" class="btn btn-success mb-3">+ پست جدید</a>
<table class="table table-bordered">
    <thead><tr><th>عنوان</th><th>نویسنده</th><th>تاریخ</th></tr></thead>
    <tbody>
        @forelse($posts as $post)
        <tr>
            <td>{{ $post->title }}</td>
            <td>{{ $post->user->name }}</td>
            <td>{{ $post->created_at->format('Y/m/d') }}</td>
        </tr>
        @empty
        <tr><td colspan="3" class="text-center">هیچ پستی نیست.</td></tr>
        @endforelse
    </tbody>
</table>
{{ $posts->links() }}
@endsection
