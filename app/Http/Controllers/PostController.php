<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Validation\ValidationException;
use Exception;
use Illuminate\Http\Request;
use Validator,Hash;
use DB,Illuminate\Database\Eloquent\ModelNotFoundException;
class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        $data= Post::all();
        if(count($data)>0){
            $response=[
                'message'=> count($data) . ' users found',
                'status' => 1,
                'data' => $data
            ];
            return response()->json($response,200);
        }
        else{
            $response=[
                'message'=> '0 users found',
                'status' => 0
            ];
            return response()->json($response,200);
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
        
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validatedData=Validator::make($request->all(), [
            'title'=>'required',
            'email'=>'required|email|unique:posts,email',
            'password'=>'required|min:8|confirmed',
            'password_confirmation'=>'required'
        ]);
        if($validatedData->fails()){
            \Log::error($validatedData->errors());
            return response()->json([
                'message'=>'validation Failed',
                'errors'=> $validatedData->errors()
            ],422);
        }
        else{
            $data=[
                'title' => $request->title,
                'email' => $request->email,
                'password' => Hash::make($request->password)
            ];
            try{
                DB::beginTransaction();
                Post::create($data);
                DB::commit();
                return response()->json([
                    'message'=> 'All ok',
                ],200);
            }
            catch(Exception $e){
                DB::rollBack();
                \Log::error($e);
                return response()->json([
                    'message'=> 'Unable to Process Your Data',
                ],500);
            }
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
        try {
            $post = Post::findOrFail($id);
            DB::beginTransaction();

            $validatedData=Validator::make($request->all(), [
                'title'=>'required',
                'email'=>'required|email|unique:posts,email',
            ]);
            if($validatedData->fails()){
                \Log::error($validatedData->errors());
                return response()->json([
                    'message'=>'validation Failed',
                    'errors'=> $validatedData->errors(),
                    'status'=>0,
                ],422);
            }

            $post->title=$request->title;
            $post->email=$request->email;
            $post->save();

            DB::commit();

            $response = [
                'message' => "Post Updated Successfully",
                'status' => 1,
            ];
            $responseCode = 200;
        } catch (ModelNotFoundException $e) {
            $response = [
                'message' => "Post Doesn't Exist",
                'status' => 0,
            ];
            $responseCode = 404;
        } catch (Exception $err) {
            DB::rollBack();
            $response = [
                'message' => "Internal Server Error",
                'error' => $err->getMessage(),
                'status' => 0,
            ];
            $responseCode = 500;
        }
        return response()->json($response, $responseCode);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $user = Post::findOrFail($id);
            DB::beginTransaction();
            $user->delete();
            DB::commit();
            $response = [
                'message' => "User Deleted Successfully",
                'status' => 1,
            ];
            $responseCode = 200;
        } catch (ModelNotFoundException $e) {
            $response = [
                'message' => "User Doesn't Exist",
                'status' => 0,
            ];
            $responseCode = 404;
        } catch (Exception $err) {
            DB::rollBack();
            $response = [
                'message' => "Internal Server Error",
                'status' => 0,
            ];
            $responseCode = 500;
        }
        return response()->json($response, $responseCode);
    }
}
