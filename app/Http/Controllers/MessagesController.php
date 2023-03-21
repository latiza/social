<?php

namespace App\Http\Controllers;


use App\Models\Message;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MessagesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
     
       $messages = Message::whereBelongsTo(Auth::user())->latest('updated_at')->paginate(5);
        return view('messages.index')->with('messages', $messages);
    
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('message.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //dd($request);
        $request->validate([
            'title' => 'required|max:120',
            'text' => 'required'
        ]);

        //message::create([
            Auth::user()->messages()->create([
            'uuid' => Str::uuid(),
            'title' => $request->title,
            'text' => $request->text
        ]);
        return to_route('messages.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Message $message)
    {
        
        if(!$message->user->is(Auth::user())){
            return abort(403);
        }
        return view('messages.show')->with('message', $message);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Message $message)
    {
       /* if($message->user_id != Auth::id()){
            return abort(403);
        }*/
        if(!$message->user->is(Auth::user())){
            return abort(403);
        }
        return view('messages.edit')->with('message', $message);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Message $message)
    {
       // dd($request);
       if($message->user_id != Auth::id()){
        return abort(403);
    }
       $request->validate([
        'title' => 'required|max:120',
        'text' => 'required'
    ]);

        $message->update([
        'title' => $request->title,
        'text' => $request->text
    ]);
        return to_route('messages.show', $message)->with('success', 'A bejegyzés frissítése megtörtént');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Message $message)
    {
        if($message->user_id != Auth::id()){
            return abort(403);
        }
        $message->delete();
        return to_route('messages.index')->with('success', 'A feljegyzés a kukába került');
    }
}
