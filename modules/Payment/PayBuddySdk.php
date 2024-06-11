<?php

namespace Modules\Payment;

use NumberFormatter;
use Illuminate\Support\Str;
use RuntimeException;

final class PayBuddySdk
{
    public function charge(string $token, int $amountInPiasters, string $statementDescription): array
    {
        $this->validateToken($token);

        $numberFormatter = new NumberFormatter('en-EG', NumberFormatter::CURRENCY);

        return [
            'id' => Str::uuid(),
            'amount_in_piasters' => $amountInPiasters,
            'localized_amount' => $numberFormatter->format($amountInPiasters / 100),
            'statement_description' => $statementDescription,
            'created_at' => now()->toDateTimeString()
        ];
    }

    public static function make(): self
    {
        return new self();
    }

    public static function validToken(): string
    {
        return (string) Str::uuid();
    }

    public  static function invalidToken(): string
    {
        return substr(self::validToken(), -35);
    }

    protected function validateToken(string $token): void
    {
        if(! Str::isUuid($token)) {
            throw new RuntimeException('the given payment token is not valid');
        }
    }
}
