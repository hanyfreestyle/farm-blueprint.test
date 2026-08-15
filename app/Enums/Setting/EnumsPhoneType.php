<?php

namespace App\Enums\Setting;

enum EnumsPhoneType: int {
  /** Fixed line numbers */
  case FIXED_LINE = 0;

  /** Mobile numbers */
  case MOBILE = 1;

  /**
   * In some regions (e.g. the USA), it is impossible to distinguish between fixed-line and
   * mobile numbers by looking at the phone number itself.
   */
  case FIXED_LINE_OR_MOBILE = 2;

  /** Freephone lines */
  case TOLL_FREE = 3;

  /** Premium rate numbers */
  case PREMIUM_RATE = 4;

  /**
   * The cost of this call is shared between the caller and the recipient, and is hence typically
   * less than PREMIUM_RATE calls. See: https://en.wikipedia.org/wiki/Shared_Cost_Service
   */
  case SHARED_COST = 5;

  /**
   * Voice over IP numbers. This includes TSoIP (Telephony Service over IP).
   */
  case VOIP = 6;

  /**
   * A personal number is associated with a particular person, and may be routed to either a
   * MOBILE or FIXED_LINE number.
   * See: https://en.wikipedia.org/wiki/Personal_Numbers
   */
  case PERSONAL_NUMBER = 7;

  /** Pager numbers */
  case PAGER = 8;

  /**
   * Universal Access Numbers or Company Numbers.
   * They may be routed to specific offices, but allow one number to be used for a company.
   */
  case UAN = 9;

  /**
   * A phone number is of type UNKNOWN when it does not fit any of the known patterns for a
   * specific region.
   */
  case UNKNOWN = 10;

  /** Emergency numbers */
  case EMERGENCY = 27;

  /** Voicemail access numbers */
  case VOICEMAIL = 28;

  /** Short codes (typically region specific) */
  case SHORT_CODE = 29;

  /** Standard rate numbers */
  case STANDARD_RATE = 30;

  /**
   * Get label from translations.
   */
  public function label(): string {
    return __('enums/general.phone_types.' . $this->name);
  }

  /**
   * Map libphonenumber integer value to our enum.
   */
  public static function fromLib(int $value): self {
    return match ($value) {
      0 => self::FIXED_LINE,
      1 => self::MOBILE,
      2 => self::FIXED_LINE_OR_MOBILE,
      3 => self::TOLL_FREE,
      4 => self::PREMIUM_RATE,
      5 => self::SHARED_COST,
      6 => self::VOIP,
      7 => self::PERSONAL_NUMBER,
      8 => self::PAGER,
      9 => self::UAN,
      10 => self::UNKNOWN,
      27 => self::EMERGENCY,
      28 => self::VOICEMAIL,
      29 => self::SHORT_CODE,
      30 => self::STANDARD_RATE,
      default => self::UNKNOWN,
    };
  }

  /**
   * Get all options for forms.
   *
   * @return array<int, string>
   */
  public static function options(): array {
    return collect(self::cases())
      ->mapWithKeys(fn (self $type) => [$type->value => $type->label()])
      ->toArray();
  }
}
