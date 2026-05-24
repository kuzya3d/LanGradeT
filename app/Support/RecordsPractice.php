<?php

namespace App\Support;

use App\Models\TestAnswer;
use App\Models\TestAttempt;
use App\Models\TestResult;
use App\Models\TestType;
use App\Services\AchievementService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

trait RecordsPractice
{
    protected function issuePracticeSubmissionToken(): string
    {
        $token = (string) Str::uuid();
        $submissions = session('practice_submissions', []);

        $submissions = collect($submissions)
            ->filter(fn (array $submission) => ($submission['created_at'] ?? 0) > now()->subHours(6)->timestamp)
            ->all();

        $submissions[$token] = [
            'attempt_id' => null,
            'created_at' => now()->timestamp,
        ];

        session(['practice_submissions' => $submissions]);

        return $token;
    }

    protected function duplicatePracticeSubmissionRedirect(Request $request): ?RedirectResponse
    {
        $token = (string) $request->input('submission_token', '');
        $submissions = session('practice_submissions', []);
        $submission = $submissions[$token] ?? null;

        if ($token === '' || $submission === null) {
            return redirect()
                ->route('tests.index')
                ->with('success', 'Эта форма теста уже устарела. Запустите тренировку заново.');
        }

        if (! empty($submission['attempt_id'])) {
            return redirect()->route('tests.attempt-result', $submission['attempt_id']);
        }

        return null;
    }

    protected function markPracticeSubmissionUsed(Request $request, TestAttempt $attempt): void
    {
        $token = (string) $request->input('submission_token', '');
        $submissions = session('practice_submissions', []);

        if ($token === '' || ! isset($submissions[$token])) {
            return;
        }

        $submissions[$token]['attempt_id'] = $attempt->id;
        $submissions[$token]['used_at'] = now()->timestamp;

        session(['practice_submissions' => $submissions]);
    }

    protected function recordPracticeAttempt(string $mode, string $title, string $legacyType, array $rows, int $xpReward = 12, int $phraseBonus = 0, bool $practiceOnly = false): array
    {
        $total = max(count($rows), 1);
        $correctCount = collect($rows)->where('is_correct', true)->count();
        $correctPhraseCount = collect($rows)->where('is_correct', true)->where('is_phrase', true)->count();
        $score = (int) round(($correctCount / $total) * 100);

        $testType = TestType::firstOrCreate(
            ['code' => $mode],
            ['title' => $title, 'description' => 'Тренировка LanGrade', 'xp_reward' => $xpReward]
        );

        $xp = match (true) {
            $score < 35 => -max(3, (int) round($testType->xp_reward * 0.35)),
            $score < 55 => -max(1, (int) round($testType->xp_reward * 0.15)),
            default => (int) round(($score / 100) * $testType->xp_reward),
        };
        $xp += $correctPhraseCount * $phraseBonus;
        $userId = Auth::id();

        $attempt = TestAttempt::create([
            'user_id' => $userId,
            'test_type_id' => $testType->id,
            'score' => $score,
            'correct_answers' => $correctCount,
            'total_questions' => $total,
            'xp_earned' => max(0, $xp),
            'payload' => [
                'xp_delta' => $xp,
                'phrase_bonus' => $correctPhraseCount * $phraseBonus,
                'practice_only' => $practiceOnly,
            ],
        ]);

        foreach ($rows as $row) {
            TestAnswer::create([
                'test_attempt_id' => $attempt->id,
                'word_id' => $row['word_id'] ?? null,
                'sentence_id' => $row['sentence_id'] ?? null,
                'question' => $row['question'] ?? null,
                'user_answer' => $row['user_answer'] ?? '',
                'correct_answer' => $row['correct_answer'] ?? '',
                'is_correct' => (bool) ($row['is_correct'] ?? false),
            ]);
        }

        TestResult::create([
            'user_id' => $userId,
            'type' => $legacyType,
            'score' => $score,
        ]);

        $earned = collect();
        if ($user = Auth::user()) {
            $xp >= 0 ? $user->increment('xp', $xp) : $user->decrement('xp', min($user->xp, abs($xp)));
            $user->refresh()->promoteEnglishLevelFromXp();
            $earned = $this->syncPracticeAchievements($user);
        }

        return [$attempt->load(['type', 'answers']), $earned];
    }

    protected function syncPracticeAchievements($user)
    {
        return app(AchievementService::class)->sync($user);
    }

    protected function normalizeAnswer($value): string
    {
        $value = mb_strtolower(trim((string) $value));
        return preg_replace('/[^\pL\pN\']/u', '', $value) ?? $value;
    }

    protected function answerMatches($given, $expected): bool
    {
        $given = $this->normalizeAnswer($given);

        if ($this->answerMatchesVariant($given, $expected)) {
            return true;
        }

        $variants = preg_split('/\s*(?:\/|;|,|\s+или\s+)\s*/u', (string) $expected) ?: [$expected];

        foreach ($variants as $variant) {
            if ($this->answerMatchesVariant($given, $variant)) {
                return true;
            }
        }

        return false;
    }

    protected function answerMatchesVariant(string $given, $expected): bool
    {
        $expected = $this->normalizeAnswer($expected);

        if ($given === $expected) {
            return true;
        }

        if ($given === '' || $expected === '') {
            return false;
        }

        $distance = levenshtein($given, $expected);
        $length = mb_strlen($expected);

        return ($length >= 5 && $distance <= 1) || ($length >= 9 && $distance <= 2);
    }
}
