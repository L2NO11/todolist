<?php

namespace App\Http\Controllers;

use App\Models\Todo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class TodoController extends Controller
{
    public function getAll(Request $request){
        $validator = Validator::make($request->all(), [
            "completed" => ['boolean']
        ]);
        if ($validator->fails()) {
            return response()->json([
                "err" => true,
                'msg' => $validator->messages()->get('*')
            ]);
        }
        $qury = Todo::where('user_id', $request->user()->id);
        if($request->completed != null){
            $qury->where("completed",$request->completed);
        }
        $todolist = $qury->get();
        return response()->json([
            "user" => $request->user(),
            "todo" => $todolist
        ]);
    }
    public function addTodo(Request $request){
        $validator = Validator::make($request->all(), [
            "content" => ["required","string","max:255"],
        ]);
        if ($validator->fails()) {
            return response()->json([
                "err" => true,
                'msg' => $validator->messages()->get('*')
            ]);
        }
        $result = Todo::create([
            "content" => $request->content,
            "user_id" => $request->user()->id,
        ]);
        if(!$result){
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
    public function findWithDate(Request $request){
        $validator = Validator::make($request->all(), [
            "date" => ["required","string","min:10","max:10",'regex:/^[1-9]{1}[\d]{3}-(0[1-9]|1[012])-(0[1-9]|[12]\d|3[01])$/i'],
            "completed" => ['boolean']
        ]);
        if ($validator->fails()) {
            return response()->json([
                "err" => true,
                'msg' => $validator->messages()->get('*')
            ]);
        }
        $qury = DB::table('todos')
            ->whereDate("created_at",$request->date)
            ->orderByDesc("created_at");
        if($request->completed != null){
            $qury->where("completed",$request->completed);
        }
        $todolist = $qury->get();
        return response()->json([
            "user" => $request->user()->id,
            "todo" => $todolist
        ]);
    }
    public function findJob(Request $request){
        $validator = Validator::make($request->all(), [
            "job" => ["required","string","min:8"],
            "completed" => ['boolean']
        ]);
        if ($validator->fails()) {
            return response()->json([
                "err" => true,
                'msg' => $validator->messages()->get('*')
            ]);
        }
        $qury = DB::table('todos')
            ->where("content",$request->job)
            ->orderByDesc("created_at");
        if($request->completed != null){
            $qury->where("completed",$request->completed);
        }
        $todolist = $qury->get();
        return response()->json([
            "user" => $request->user()->id,
            "todo" => $todolist
        ]);
    }
    public function changeJobName(Request $request){
        $validator = Validator::make($request->all(), [
            "fname" => ["required","string","min:8"],
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
    public function makeComplete(Request $request){
        $validator = Validator::make($request->all(), [
            "id" => ["required","string"],
        ]);
        if ($validator->fails()) {
            return response()->json([
                "err" => true,
                'msg' => $validator->messages()->get('*')
            ]);
        }
        Todo::find($request->id)
            ->update([
                "completed" => true
            ]);
        return response()->json([
            "msg" => "success"
        ]);
    }
    public function deleteJob(Request $request){
        $validator = Validator::make($request->all(), [
            "id" => ["required","string"],
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
