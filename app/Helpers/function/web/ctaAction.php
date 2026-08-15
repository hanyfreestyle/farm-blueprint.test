<?php
#@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
#||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||
use App\Enums\Setting\EnumsSocialPlatform;
use libphonenumber\NumberParseException;
use libphonenumber\PhoneNumberFormat;
use libphonenumber\PhoneNumberType;
use libphonenumber\PhoneNumberUtil;

#@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
#||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||
if (!function_exists('ctaActionCallWithVerification')) {
  function ctaActionCallWithVerification($data, $name): array {
    $phoneCountry = $name . "_country";
    $phoneNumber = $data->{$name} ?? null;
    $phoneNumberCountry = $data->{$phoneCountry} ?? null;

    if ($phoneNumber and $phoneNumberCountry) {
      return phoneValidationNumber($phoneNumber, $phoneNumberCountry);
    }

    return [
      'valid_number' => false,
      'valid_type' => null,
      'full_number' => null,
      'whatsapp_number' => null,
      'country' => null,
    ];

  }
}
#@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
#||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||
if (!function_exists('phoneValidationNumber')) {
  function phoneValidationNumber(string $phone, $country): array {
    $utils = PhoneNumberUtil::getInstance();
    $acceptedTypes = [
      PhoneNumberType::MOBILE,
    ];
    $numberProto = $utils->parse($phone, $country);

    if ($utils->isValidNumberForRegion($numberProto, $country)) {
      $numberType = $utils->getNumberType($numberProto);
      if (in_array($numberType, $acceptedTypes)) {
        $formatted = $utils->format($numberProto, PhoneNumberFormat::E164);
        return [
          'valid_number' => true,
          'valid_type' => $numberType->name,
          'full_number' => $formatted,
          'whatsapp_number' => str_replace('+', '', $formatted),
          'country' => $country,
        ];
      }
    }

    return [
      'valid_number' => false,
      'valid_type' => null,
      'full_number' => null,
      'whatsapp_number' => null,
      'country' => null,
    ];
  }
}


#@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
#||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||

if (!function_exists('ctaActionCall')) {
  function ctaActionCall($webConfig): string {
    return "tel:" . $webConfig->phone_call;
  }
}


#@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
#||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||
if (!function_exists('ctaActionCallIcon')) {
  function ctaActionCallIcon(): string {
    if (thisCurrentLocale() == 'en') {
      return ' fa-phone';
    } else {
      return ' fa-phone-alt';
    }
  }
}

#@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
#||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||
if (!function_exists('ctaActionMap')) {
  function ctaActionMap($webConfig): string {
    return $webConfig->google_map_url ?? '';
  }
}

#@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
#||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||
if (!function_exists('ctaActionWhatsapp')) {
  function ctaActionWhatsapp($webConfig, $addDes = null): string {
    $brek = "%0a";

    if ($addDes) {
      $getMass = $addDes;
    } else {
      $getMass = $webConfig->whatsapp_des;
    }

//     dd($webConfig->whatsapp_send);
    $Mass = str_replace(" ", "+", $getMass);
    $Mass = str_replace("*", "%2A", $Mass);
    $Mass = str_replace("#", "%23", $Mass);

//    $whatsappUrl = 'https://api.whatsapp.com/send/?phone=' . $webConfig->whatsapp_send . '&text=' . $Mass;
//    $whatsappUrl = 'https://wa.me/201031873351?text=' . $Mass;
    $whatsappUrl = 'https://wa.me/' . $webConfig->whatsapp_send . '?text=' . $Mass;

//    $whatsappUrl = 'https://wa.me/?phone=' . $webConfig->whatsapp_send . '&text=' . $Mass;
//    $whatsappUrl = 'https://wa.me/?phone=' . $webConfig->whatsapp_send . '&text=' . $Mass;
//    $whatsappUrl =  "https://wa.me/".$webConfig->whatsapp_send;
//    $whatsappUrl =  "https://api.whatsapp.com/send/?phone=21110510003";
    return $whatsappUrl;
  }
}


#@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
#||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||
if (!function_exists('ctaActionWhatsappWithData')) {
  function ctaActionWhatsappWithData($webConfig, $addDes = null): string {
    $brek = "%0a";

    if ($addDes) {
      $getMass = $webConfig->whatsapp_des . " " . $addDes;
    } else {
      $getMass = $webConfig->whatsapp_des;
    }

    $Mass = str_replace(" ", "+", $getMass);
    $Mass = str_replace("*", "%2A", $Mass);
    $Mass = str_replace("#", "%23", $Mass);


    $whatsappUrl = 'https://wa.me/' . $webConfig->whatsapp_send . '?text=' . $Mass;

    return $whatsappUrl;
  }
}


#@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
#||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||
if (!function_exists('lostPetWhatsapp')) {
  function lostPetWhatsapp($data): string {
    $brek = "%0a";
    $getMass = __('aleefak/web/default.lost_page.whatsapp_mass.line_1') . $brek;
    $getMass .= __('aleefak/web/default.lost_page.whatsapp_mass.line_2');

    $Mass = str_replace(" ", "+", $getMass);
    $Mass = str_replace("*", "%2A", $Mass);
    $Mass = str_replace("#", "%23", $Mass);
    $whatsappUrl = 'https://wa.me/' . $data['whatsapp_number'] . '?text=' . $Mass;

    return $whatsappUrl;
  }
}

#@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
#||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||
if (!function_exists('social_platform_icon')) {
  function social_platform_icon(array $social): string {
    $platform = EnumsSocialPlatform::tryFrom($social['platform']);
    return $platform->getIcon();
  }
}

function capitalize_first_letter(?string $text): string {
  $text = trim((string)$text);

  if ($text === '') {
    return '';
  }

  return mb_strtoupper(mb_substr($text, 0, 1, 'UTF-8'), 'UTF-8')
    . mb_substr($text, 1, null, 'UTF-8');
}









