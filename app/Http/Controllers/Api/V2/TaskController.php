<?php

namespace App\Http\Controllers\Api\V2;

use App\Http\Requests\StoreTaskRequest;
use App\Http\Requests\UpdateTaskRequest;
use App\Http\Controllers\Controller;
use App\Models\Task;
use App\Http\Resources\TaskResource;
use Illuminate\Support\Facades\Gate;
use Illuminate\Http\Request;


class TaskController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //



        Gate::authorize('viewAny', Task::class);

        //return new TaskResource::collection(Task::all());
        return request()->user()->tasks()->toResourceCollection();
    }

   

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreTaskRequest $request)
    {
        //

        
        Gate::authorize('create', Task::class);

        // $task  = Task::create($request->validated() + [
        //     'user_id' => $request->user()->id
        // ]);

        $task = $request()->user()->tasks()->create($request->validated());

        return $task->toResource();
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request, Task $task)
    { 
        //
        //return response()->json(['data'=> null], 200);

        // using policies
        // if ($request->user()->cannot('view', $task)) {
        //     abort(403);
        // }

        Gate::authorize('view', $task);

        return new TaskResource($task);
        //return TaskResource::make($task);
        //return $task->toResourceCollection();

    }

   

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateTaskRequest $request, Task $task)
    {
        Gate::authorize('update', $task);
        //
        $task->update($request->validated());
        //Task::update( $request->validated() );
        
        return $task->toResource();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Task $task)
    {
        //

        Gate::authorize('delete', $task);

        $task->delete();
        // for 204 success response
        return response()->noContent();
    }
}
