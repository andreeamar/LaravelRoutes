@extends('layouts.app')

@section('title', 'Add task')

@section('content')
    <form method="POST" action="{{ route('tasks.update', ['id' => $task->id]) }}">
        {{-- this directive protects from attacks --}}
        @csrf
        @method('PUT')
        <div>
            <label for="title">Title</label>
            <input type="text" name="title" id="title" value={{ $task->title }}>
            @error('title')
                <p>{{ $message }}</p>
            @enderror
        </div>
        <div>
            <label for="description">Description</label>
            <textarea id="description" name="description" rows="5" value={{ $task->description }}></textarea>
            @error('description')
                <p>{{ $message }}</p>
            @enderror
        </div>
        <div>
            <button type="submit">Add Task</button>
        </div>
    </form>
@endsection
