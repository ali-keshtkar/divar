<?php

namespace Modules\Otp\Entities;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Modules\Otp\Database\factories\OtpFactory;

class Otp extends Model
{
    #region Traits

    use HasFactory;

    #endregion

    #region Constructions

    protected $fillable = [
        'phone_number',
        'code',
    ];
    protected $casts = [
        'code' => 'integer',
    ];

    #endregion

    #region Public Methods

    /**
     * Initialize instance of model.
     *
     * @return Otp
     */
    public static function new(): Otp
    {
        return new self();
    }

    /**
     * Check for code existence or not!
     *
     * @param string $phone_number
     * @return static
     */
    public function requestCode(string $phone_number): static
    {
        /** @var Otp $otp */
        $otp = $this->_requestOtp($phone_number);
        return $otp->_checkRequestExpiration();
    }

    #endregion

    #region Private Methods

    /**
     * First or create otp with specified phone number.
     *
     * @param string $phone_number
     * @return Model|Builder
     */
    private function _requestOtp(string $phone_number): Model|Builder
    {
        return self::query()->firstOrCreate([
            'phone_number' => $phone_number,
        ], [
            'phone_number' => $phone_number,
            'code' => $this->_generateRandomUniqueCode(),
        ]);
    }

    /**
     * If code is expired generate a new random code otherwise doesn't update anything.
     *
     * @return static
     */
    private function _checkRequestExpiration(): static
    {
        if ($this->_checkCodeIsExpiredAfterSpecifiedPeriod()) {
            $this->update(['code' => $this->_generateRandomUniqueCode()]);
            return $this->refresh();
        } else {
            return $this;
        }
    }

    /**
     * Check code is expired or not after specified period from config.
     *
     * @return bool
     */
    private function _checkCodeIsExpiredAfterSpecifiedPeriod(): bool
    {
        return now()->gt($this->updated_at->addSeconds(config('otp.expiration_period')));
    }

    /**
     * Generate a random code with specified limit digits.
     *
     * @return int
     */
    private function _generateRandomUniqueCode(): int
    {
        $digits = config('otp.random_code_length');
        do {
            $code = rand(pow(10, $digits - 1), pow(10, $digits) - 1);
        } while ($this->_checkCodeExists($code));
        return $code;
    }

    /**
     * Check code exists in database.
     *
     * @param int $code
     * @return bool
     */
    private function _checkCodeExists(int $code): bool
    {
        return self::query()->where(['code' => $code])->exists();
    }

    #endregion

    #region Protected Methods

    /**
     * Declare Models Factory.
     *
     * @return OtpFactory
     */
    protected static function newFactory(): OtpFactory
    {
        return OtpFactory::new();
    }

    #endregion
}
