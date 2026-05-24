<?php

namespace Tests\Feature;

use App\Models\TestAttempt;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PracticeSubmissionSecurityTest extends TestCase
{
    use RefreshDatabase;

    public function test_reloading_practice_submit_does_not_create_second_attempt_or_xp(): void
    {
        $user = User::factory()->create(['xp' => 0]);
        $token = 'practice-token';
        $payload = [
            'submission_token' => $token,
            'questions' => ['0' => '[həˈloʊ]'],
            'correct' => ['0' => 'hello'],
            'answers' => ['0' => 'hello'],
            'is_phrase' => ['0' => 0],
        ];

        $this
            ->actingAs($user)
            ->withSession([
                'practice_submissions' => [
                    $token => [
                        'attempt_id' => null,
                        'created_at' => now()->timestamp,
                    ],
                ],
            ])
            ->post(route('tests.modern-submit', 'phonetics'), $payload)
            ->assertRedirect();

        $attempt = TestAttempt::first();

        $this->assertNotNull($attempt);
        $this->assertSame(1, TestAttempt::count());
        $this->assertSame(12, $user->refresh()->xp);

        $this
            ->actingAs($user)
            ->post(route('tests.modern-submit', 'phonetics'), $payload)
            ->assertRedirect(route('tests.attempt-result', $attempt));

        $this->assertSame(1, TestAttempt::count());
        $this->assertSame(12, $user->refresh()->xp);
    }

    public function test_submit_without_valid_submission_token_is_rejected(): void
    {
        $user = User::factory()->create(['xp' => 0]);

        $this
            ->actingAs($user)
            ->post(route('tests.modern-submit', 'phonetics'), [
                'questions' => ['0' => '[həˈloʊ]'],
                'correct' => ['0' => 'hello'],
                'answers' => ['0' => 'hello'],
            ])
            ->assertRedirect(route('tests.index'));

        $this->assertSame(0, TestAttempt::count());
        $this->assertSame(0, $user->refresh()->xp);
    }
}
