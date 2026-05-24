<?php

namespace Tests\Feature;

use App\Models\Conversation;
use App\Models\Message;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class CommunityMessageSecurityTest extends TestCase
{
    use RefreshDatabase;

    public function test_community_message_body_is_encrypted_at_rest(): void
    {
        $sender = User::factory()->create();
        $receiver = User::factory()->create();
        $conversation = Conversation::create([
            'subject' => 'Practice',
            'created_by' => $sender->id,
        ]);
        $conversation->users()->attach([$sender->id, $receiver->id]);

        $message = Message::create([
            'conversation_id' => $conversation->id,
            'user_id' => $sender->id,
            'body' => 'Secret community message',
        ]);

        $rawBody = DB::table('messages')->where('id', $message->id)->value('body');

        $this->assertNotSame('Secret community message', $rawBody);
        $this->assertSame('Secret community message', $message->refresh()->body);
    }

    public function test_non_participant_cannot_reply_to_someone_elses_conversation(): void
    {
        $sender = User::factory()->create();
        $receiver = User::factory()->create();
        $stranger = User::factory()->create();
        $conversation = Conversation::create([
            'subject' => 'Practice',
            'created_by' => $sender->id,
        ]);
        $conversation->users()->attach([$sender->id, $receiver->id]);

        $this
            ->actingAs($stranger)
            ->post(route('community.reply', $conversation), ['body' => 'I should not enter'])
            ->assertForbidden();

        $this->assertDatabaseMissing('messages', [
            'conversation_id' => $conversation->id,
            'user_id' => $stranger->id,
        ]);
    }

    public function test_updates_return_only_authenticated_users_conversations(): void
    {
        $owner = User::factory()->create();
        $participant = User::factory()->create();
        $stranger = User::factory()->create();
        $conversation = Conversation::create([
            'subject' => 'Private practice',
            'created_by' => $owner->id,
        ]);
        $conversation->users()->attach([$owner->id, $participant->id]);
        Message::create([
            'conversation_id' => $conversation->id,
            'user_id' => $owner->id,
            'body' => 'Visible only to participants',
        ]);

        $this
            ->actingAs($stranger)
            ->getJson(route('community.updates'))
            ->assertOk()
            ->assertJsonPath('conversations', []);

        $this
            ->actingAs($participant)
            ->getJson(route('community.updates'))
            ->assertOk()
            ->assertJsonPath('conversations.0.id', $conversation->id)
            ->assertJsonPath('conversations.0.messages.0.body', 'Visible only to participants');
    }
}
