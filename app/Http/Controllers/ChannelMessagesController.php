<?php

namespace App\Http\Controllers;

use App\Channel;
use App\Http\Requests\Channels\SendMessageRequest;
use App\Events\NewMessage;
use Illuminate\Support\Carbon;

class ChannelMessagesController extends Controller
{
    /**
     * @param \Illuminate\Http\Request $request
     *
     * @return mixed
     */
    public function index(Channel $channel)
    {
        return response()->json(['data' => $channel->messages], 201);
    }

    /**
     * @param \App\Http\Requests\Channels\SendMessageRequest $request
     * @param \App\Channel $channel
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(SendMessageRequest $request, Channel $channel)
    {
        $message = new NewMessage(
            $request->user(),
            $channel,
            $request->input('content'),
            $request->input('uuid'),
            Carbon::now()
        );

        $messages = $channel->messages;
        $messages[] = array(
            'user' => $request->user(),
            'channel' => null,
            'content' => $request->input('content'),
            'uuid' => $request->input('uuid'),
            'sentAt' => Carbon::now()
        );

        $channel->messages = $messages;
        $channel->save();

        broadcast($message);

        return response()->json('', 201);
    }
}
