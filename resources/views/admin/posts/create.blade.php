@extends('layouts.admin')
@section('content')
<h3>ایجاد پست جدید</h3>
<form action="{{ route('admin.posts.store') }}" method="POST">
    @csrf
    <div class="mb-3">
        <label>عنوان</label>
        <input type="text" name="title" class="form-control" required>
    </div>
    <div class="mb-3">
        <label>اسلاگ (slug)</label>
        <input type="text" name="slug" class="form-control" required>
    </div>
    <div class="mb-3">
        <label>محتوا</label>
        <textarea name="content" class="form-control" rows="8" required></textarea>
    </div>
    <button type="submit" class="btn btn-primary">ذخیره</button>
    <a href="{{ route('admin.posts.index') }}" class="btn btn-secondary">انصراف</a>
</form>
@endsection
