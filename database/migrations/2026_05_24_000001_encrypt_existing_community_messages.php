<?php

use Illuminate\Contracts\Encryption\DecryptException;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::table('messages')
            ->select(['id', 'body'])
            ->orderBy('id')
            ->lazyById()
            ->each(function (object $message): void {
                if ($message->body === null || $this->isEncrypted($message->body)) {
                    return;
                }

                DB::table('messages')
                    ->where('id', $message->id)
                    ->update(['body' => Crypt::encryptString($message->body)]);
            });
    }

    public function down(): void
    {
        DB::table('messages')
            ->select(['id', 'body'])
            ->orderBy('id')
            ->lazyById()
            ->each(function (object $message): void {
                if ($message->body === null) {
                    return;
                }

                try {
                    $plainText = Crypt::decryptString($message->body);
                } catch (DecryptException) {
                    return;
                }

                DB::table('messages')
                    ->where('id', $message->id)
                    ->update(['body' => $plainText]);
            });
    }

    private function isEncrypted(string $value): bool
    {
        try {
            Crypt::decryptString($value);

            return true;
        } catch (DecryptException) {
            return false;
        }
    }
};
