<?php

namespace App\Http\Controllers;

use App\Models\Conversation;
use App\Models\Friendship;
use App\Models\Message;
use App\Models\User;
use App\Services\AchievementService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CommunityController extends Controller
{
    public function index()
    {
        $friendIds = Friendship::where('user_id', Auth::id())->where('status', 'accepted')->pluck('friend_id');
        $users = User::whereIn('id', $friendIds)->orderByDesc('xp')->get();
        $allUsers = User::where('id', '!=', Auth::id())->whereNotIn('id', $friendIds)->orderByDesc('xp')->take(20)->get();
        $conversations = Auth::user()->conversations()->with(['messages.user'])->latest('updated_at')->get();

        return view('community.index', compact('users', 'allUsers', 'conversations'));
    }

    public function updates()
    {
        return response()->json([
            'conversations' => $this->conversationPayload(),
        ]);
    }

    public function start(Request $request)
    {
        $data = $request->validate([
            'user_id' => ['required', 'exists:users,id'],
            'subject' => ['nullable', 'string', 'max:255'],
            'body' => ['required', 'string', 'max:2000'],
        ]);

        abort_unless(Auth::user()->friends()->where('friend_id', $data['user_id'])->exists(), 403);

        $conversation = Conversation::create([
            'subject' => $data['subject'] ?: 'Практика английского',
            'created_by' => Auth::id(),
        ]);

        $conversation->users()->attach([Auth::id(), $data['user_id']]);
        Message::create([
            'conversation_id' => $conversation->id,
            'user_id' => Auth::id(),
            'body' => $data['body'],
        ]);
        $conversation->touch();

        if ($request->expectsJson()) {
            return response()->json([
                'conversation' => $this->conversationPayload($conversation->id)->first(),
            ]);
        }

        return redirect()
            ->route('community.index')
            ->with('success', 'Сообщение отправлено.')
            ->with('open_conversation', $conversation->id);
    }

    public function reply(Request $request, Conversation $conversation)
    {
        $this->authorizeConversationParticipant($conversation);

        $data = $request->validate(['body' => ['required', 'string', 'max:2000']]);
        Message::create([
            'conversation_id' => $conversation->id,
            'user_id' => Auth::id(),
            'body' => $data['body'],
        ]);
        $conversation->touch();

        if ($request->expectsJson()) {
            return response()->json([
                'conversation' => $this->conversationPayload($conversation->id)->first(),
            ]);
        }

        return redirect()
            ->route('community.index')
            ->with('open_conversation', $conversation->id);
    }

    public function destroy(Conversation $conversation)
    {
        $this->authorizeConversationParticipant($conversation);
        $conversation->delete();

        return redirect()->route('community.index')->with('success', 'Диалог удален.');
    }

    public function addFriend(User $user)
    {
        abort_if($user->id === Auth::id(), 422);

        Friendship::updateOrCreate(['user_id' => Auth::id(), 'friend_id' => $user->id], ['status' => 'accepted']);
        Friendship::updateOrCreate(['user_id' => $user->id, 'friend_id' => Auth::id()], ['status' => 'accepted']);
        app(AchievementService::class)->sync(Auth::user());

        return back()->with('success', 'Пользователь добавлен в друзья.');
    }

    public function removeFriend(User $user)
    {
        Friendship::where('user_id', Auth::id())->where('friend_id', $user->id)->delete();
        Friendship::where('user_id', $user->id)->where('friend_id', Auth::id())->delete();

        return back()->with('success', 'Пользователь удален из друзей.');
    }

    private function authorizeConversationParticipant(Conversation $conversation): void
    {
        abort_unless($conversation->users()->where('user_id', Auth::id())->exists(), 403);
    }

    private function conversationPayload(?int $conversationId = null)
    {
        $query = Auth::user()
            ->conversations()
            ->with(['messages.user'])
            ->latest('updated_at');

        if ($conversationId) {
            $query->where('conversations.id', $conversationId);
        }

        return $query->get()->map(fn (Conversation $conversation) => [
            'id' => $conversation->id,
            'subject' => $conversation->subject,
            'messages_count' => $conversation->messages->count(),
            'updated_at' => optional($conversation->updated_at)->toIso8601String(),
            'messages' => $conversation->messages->map(fn (Message $message) => [
                'id' => $message->id,
                'body' => $message->body,
                'created_at' => optional($message->created_at)->format('H:i'),
                'is_mine' => $message->user_id === Auth::id(),
                'user' => [
                    'id' => $message->user->id,
                    'name' => $message->user->name,
                    'avatar' => $message->user->avatar ?: '🙂',
                    'url' => route('users.show', $message->user),
                ],
            ])->values(),
        ])->values();
    }
}
