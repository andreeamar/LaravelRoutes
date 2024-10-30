<?php

use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Route;

// class Task
// {
//     public function __construct(
//         public int $id,
//         public string $title,
//         public string $description,
//         public ?string $long_description,
//         public bool $completed,
//         public string $created_at,
//         public string $updated_at
//     ) {
//     }
// }

function fetchData()
{
    $response = Http::get('http://swapi.dev/api/planets/1/');

    if ($response->successful()) {
        // Decode the JSON response
        $data = $response->json();

        // Output the data or return it as a response
        return response()->json($data);
    } else {
        // Handle error
        return response()->json(['error' => 'Unable to fetch data'], $response->status());
    }
}

Route::get('/', function () {
    return redirect()->route('tasks.index');
});

Route::get('/fetch-data', function () {
    return fetchData();
});

Route::get('/tasks', function () {
    // use blade templates to render dynamic content 
    return view('index', ['tasks' => App\Models\Task::latest()->get()]);
})->name('tasks.index');

Route::view('/tasks/create', 'create')->name('tasks.create');

Route::get('/tasks/{id}/edit', function ($id) {
    return view('edit', ['task' => App\Models\Task::findOrFail($id)]);
    // convention name of the resource controllers 
})->name('tasks.show');

Route::get('/tasks/{id}', function ($id) {
    return view('show', ['task' => App\Models\Task::findOrFail($id)]);
    // convention name of the resource controllers 
})->name('tasks.show');

Route::post('/tasks', function (Request $request) {
    $data = $request->validate([
        'title' => 'required|max:255',
        'description' => 'required',
    ]);
    $task = new Task;
    $task->title = $data['title'];
    $task->description = $data['description'];

    $task->save();

    return redirect()->route('tasks.show', ['id' => $task->id]);
})->name('tasks.store');

Route::put('/tasks/{id}', function ($id, Request $request) {
    $data = $request->validate([
        'title' => 'required|max:255',
        'description' => 'required',
    ]);
    $task = Task::findOrFail($id);
    $task->title = $data['title'];
    $task->description = $data['description'];

    $task->save();

    return redirect()->route('tasks.show', ['id' => $task->id])->with('success', 'Task updated successfully!');
})->name('tasks.update');

Route::fallback(function () {
    return 'Not found!';
});
