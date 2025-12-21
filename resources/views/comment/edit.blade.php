@extends('layout')

@section('content')

<h3>Edit comment</h3>

@if ($errors->any())
    <ul class="list-group mb-3">
        @foreach ($errors->all() as $error)
            <li class="list-group-item list-group-item-danger">
                {{ $error }}
            </li>
        @endforeach
    </ul>
@endif

<form action="{{ url('/comment/update/' . $comment->id) }}" method="POST">
    @csrf

    <div class="mb-3">
        <label for="text" class="form-label">Comment text</label>
        <textarea name="text"
                  id="text"
                  rows="4"
                  class="form-control"
                  required>{{ $comment->text }}</textarea>
    </div>

    <button type="submit" class="btn btn-primary">
        Save
    </button>

    <a href="{{ route('article.show', $comment->article_id) }}"
       class="btn btn-secondary">
        Cancel
    </a>
</form>

@endsection
