<?php

namespace App\Http\Controllers;

use App\Models\Todo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class TodoController extends Controller
{
    public function getAll(Request $request)
    {
        $validator = Validator::make($request->all(), [
            "completed" => ["required", 'boolean'],
            "page" => ["required", "integer"]
        ]);
        if ($validator->fails()) {
            return response()->json([
                "err" => true,
                'msg' => $validator->messages()->get('*')
            ], 200);
        }
        $quary = Todo::where(
            [
                'user_id' => $request->user()->id,
                'completed' => $request->completed
            ]
        )
            ->orderBy('id', 'DESC');
        $count =  ceil($quary->count() / 5);
        if ($count < $request->page || $request->page < 0) {
            return response()->json(["err" => true, "msg" => "Notfound page"], 200);
        }
        $todolist = $quary->offset(5 * ($request->page - 1))
            ->limit(5)
            ->get();
        return response()->json([
            "countPage" => $count,
            "todo" => $todolist
        ]);
    }
    public function addTodo(Request $request)
    {
        $validator = Validator::make($request->all(), [
            "content" => ["required", "string", "max:255"],
            "at" => ["required"],
        ]);
        if ($validator->fails()) {
            return response()->json([
                "err" => true,
                'msg' => $validator->messages()->get('*')
            ]);
        }
        $result = Todo::create([
            "content" => $request->content,
            "at" => $request->at,
            "user_id" => $request->user()->id,
        ]);
        if (!$result) {
            return response()->json([
                "err" => true,
                'msg' => 'can not register user'
            ]);
        }
        return response()->json([
            "err" => false,
            'msg' => 'success'
        ]);
    }
    /////////////////////////////////
    public function findWithDate(Request $request)
    {
        $validator = Validator::make($request->all(), [
            "date" => ["required", "string", "min:10", "max:10", 'regex:/^[1-9]{1}[\d]{3}-(0[1-9]|1[012])-(0[1-9]|[12]\d|3[01])$/i'],
            "completed" => ['boolean'],
            "page" => ["required", "integer"]
        ]);
        if ($validator->fails()) {
            return response()->json([
                "err" => true,
                'msg' => $validator->messages()->get('*')
            ]);
        }
        $qury = DB::table('todos')
            ->where("user_id", $request->user()->id)
            ->where('completed', $request->completed)
            ->whereDate("at", $request->date)
            ->orderByDesc("id");
        $count =  ceil(($qury->count('id')) / 5);
        if ($count < $request->page || $request->page < 0) {
            return response()->json(["err" => true, "msg" => "Notfound page"], 200);
        }
        $todolist = $qury->offset(5 * ($request->page - 1))
            ->limit(5)->get();
        return response()->json([
            "user" => $request->user()->id,
            "countPage" => $count,
            "todo" => $todolist
        ]);
    }
    ////////////////////////////////////////////////
    public function findJob(Request $request)
    {
        $validator = Validator::make($request->all(), [
            "job" => ["required", "string", "min:1"],
            "completed" => ["required", 'boolean'],
            "page" => ["required", "integer"]
        ]);
        if ($validator->fails()) {
            return response()->json([
                "err" => true,
                'msg' => $validator->messages()->get('*')
            ]);
        }
        $qury = DB::table('todos')
            ->where("user_id", $request->user()->id)
            ->where('completed', $request->completed)
            ->where("content", $request->job)
            ->orderByDesc("created_at");
        $count =  ceil(($qury->count('id')) / 5);
        $todolist = $qury->offset(5 * ($request->page - 1))
            ->limit(5)->get();
        return response()->json([
            "user" => $request->user()->id,
            "countPage" => $count,
            "todo" => $todolist
        ]);
    }
    //////////////////////////////////////////
    public function changeJobName(Request $request)
    {
        $validator = Validator::make($request->all(), [
            "fname" => ["required", "string", "min:8"],
        ]);
        if ($validator->fails()) {
            return response()->json([
                "err" => true,
                'msg' => $validator->messages()->get('*')
            ]);
        }
        Todo::find($request->id)
            ->update([
                "content" => $request->newContent
            ]);
        return response()->json([
            "msg" => "success"
        ]);
    }
    public function makeComplete(Request $request)
    {
        $validator = Validator::make($request->all(), [
            "id" => ["required", "string"],
        ]);
        if ($validator->fails()) {
            return response()->json([
                "err" => true,
                'msg' => $validator->messages()->get('*'),
            ]);
        }
        $todo = Todo::find($request->id);
        $todo->completed = true;
        $todo->update();

        return response()->json([
            "msg" => "success",
            "sss" => $todo
        ]);
    }
    public function deleteJob(Request $request)
    {
        $validator = Validator::make($request->all(), [
            "id" => ["required", "string"],
        ]);
        if ($validator->fails()) {
            return response()->json([
                "err" => true,
                'msg' => $validator->messages()->get('*')
            ]);
        }
        Todo::find($request->id)->delete();
        return response()->json([
            "msg" => "success"
        ]);
    }
}
