<?php

use libphonenumber\PhoneNumberUtil;
use libphonenumber\PhoneNumberFormat;
use libphonenumber\NumberParseException;
use libphonenumber\PhoneNumberType;

/**
 * تنسيق رقم الهاتف + استخراج كود الدولة
 *
 * @param string|null $rawPhone
 * @param string|null $defaultCountryCode
 * @param string $formatText
 * @return array ['formatted' => ?string, 'regionCode' => ?string, 'error' => ?string]
 */

#@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
#||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||
if (!function_exists('formatPhoneNumber')) {
//    function formatPhoneNumber(?string $rawPhone, ?string $defaultCountryCode = null, string $formatText = 'E164'): array {
//        $phoneUtil = PhoneNumberUtil::getInstance();
//        try {
//            if (!$rawPhone) {
//                return ['formatted' => null, 'regionCode' => null, 'error' => true, 'error_text' => 'الرقم فارغ'];
//            }
//
//            $formatType = match (strtoupper($formatText)) {
//                'E164' => PhoneNumberFormat::E164,
//                'INTERNATIONAL' => PhoneNumberFormat::INTERNATIONAL,
//                'NATIONAL' => PhoneNumberFormat::NATIONAL,
//                'RFC3966' => PhoneNumberFormat::RFC3966,
//                default => PhoneNumberFormat::E164,
//            };
//
//            // التحقق من وجود كود الدولة أو رمز +
//            if (is_null($defaultCountryCode) && !str_starts_with($rawPhone, '+')) {
//                return ['formatted' => null, 'regionCode' => null, 'error' => true, 'error_text' => 'يجب تحديد الدولة إذا لم يبدأ الرقم بـ +'];
//            }
//
//            $phoneProto = $phoneUtil->parse($rawPhone, $defaultCountryCode);
//
//
//            if (!$phoneUtil->isValidNumber($phoneProto)) {
//                return ['formatted' => null, 'regionCode' => null, 'error' => true, 'error_text' => 'رقم غير صالح'];
//            }
//
//            $formatted = $phoneUtil->format($phoneProto, $formatType);
//            $regionCode = $phoneUtil->getRegionCodeForNumber($phoneProto);
//            return ['formatted' => $formatted, 'regionCode' => $regionCode, 'error' => false ,'error_text' => null];
//
//        } catch (NumberParseException $e) {
//            return ['formatted' => null, 'regionCode' => null, 'error' => true, 'error_text' => $e->getMessage()];
//        }
//    }

    function formatPhoneNumber(?string $rawPhone, ?string $defaultCountryCode = null, string $formatText = 'E164', string $validTypeText = 'MOBILE'): array {
        $phoneUtil = PhoneNumberUtil::getInstance();

        try {
            if (!$rawPhone) {
                return ['formatted' => null, 'regionCode' => null, 'error' => true, 'error_text' => 'الرقم فارغ'];
            }

            // تحويل النص إلى تنسيق فعلي
            $formatType = match (strtoupper($formatText)) {
                'E164' => PhoneNumberFormat::E164,
                'INTERNATIONAL' => PhoneNumberFormat::INTERNATIONAL,
                'NATIONAL' => PhoneNumberFormat::NATIONAL,
                'RFC3966' => PhoneNumberFormat::RFC3966,
                default => PhoneNumberFormat::E164,
            };

            // تحويل نوع الرقم من النص إلى رقم ثابت
            $validType = match (strtoupper($validTypeText)) {
                'MOBILE' => PhoneNumberType::MOBILE,
                'FIXED_LINE' => PhoneNumberType::FIXED_LINE,
                'FIXED_LINE_OR_MOBILE' => PhoneNumberType::FIXED_LINE_OR_MOBILE,
                'TOLL_FREE' => PhoneNumberType::TOLL_FREE,
                'VOIP' => PhoneNumberType::VOIP,
                'PAGER' => PhoneNumberType::PAGER,
                'UAN' => PhoneNumberType::UAN,
                'VOICEMAIL' => PhoneNumberType::VOICEMAIL,
                'SHARED_COST' => PhoneNumberType::SHARED_COST,
                default => PhoneNumberType::MOBILE, // الافتراضي
            };

            if (is_null($defaultCountryCode) && !str_starts_with($rawPhone, '+')) {
                return ['formatted' => null, 'regionCode' => null, 'error' => true, 'error_text' => 'يجب تحديد الدولة إذا لم يبدأ الرقم بـ +'];
            }

            $phoneProto = $phoneUtil->parse($rawPhone, $defaultCountryCode);

            if (!$phoneUtil->isValidNumber($phoneProto)) {
                return ['formatted' => null, 'regionCode' => null, 'error' => true, 'error_text' => 'رقم غير صالح'];
            }

            $actualType = $phoneUtil->getNumberType($phoneProto);

            if ($actualType !== $validType) {
                return ['formatted' => null, 'regionCode' => null, 'error' => true, 'error_text' => 'نوع الرقم لا يتطابق مع النوع المطلوب'];
            }

            $formatted = $phoneUtil->format($phoneProto, $formatType);
            $regionCode = $phoneUtil->getRegionCodeForNumber($phoneProto);

            return [
                'formatted' => $formatted,
                'regionCode' => $regionCode,
                'type' => $validTypeText,
                'error' => false,
                'error_text' => null
            ];

        } catch (NumberParseException $e) {
            return ['formatted' => null, 'regionCode' => null, 'error' => true, 'error_text' => $e->getMessage()];
        }
    }
}


