<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Events\PusherBroadcast;
use App\Models\User;

class PusherController extends Controller
{
    public function index()
    {
        return view('index');
    }

    public function broadcast(Request $request)
    {
        $imageUrl = '';
        if ($request->file('file')) {
            $user = User::find($request->get('user_id'));
            $user->addMedia($request->file('file'))->toMediaCollection('images');
            $imageUrl = $user->getFirstMediaUrl('images');
        }

        broadcast(new PusherBroadcast($request->get('message') ?? '', $request->get('user_id'), $imageUrl))->toOthers();

        return view('broadcast', [
            'message' => $request->get('message'),
            'user_id' => $request->get('user_id'),
            'fileUrl' => $imageUrl,
        ]);
    }

    public function receive(Request $request)
    {
        return view('receive', [
            'message' => $request->get('message'),
            'user_id' => $request->get('user_id'),
            'fileUrl' => $request->get('file'),
        ]);
    }
}
