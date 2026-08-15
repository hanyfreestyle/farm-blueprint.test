<?php

namespace App\FilamentHelpers\Form\Setting;

class LoadDefaultSettings {

#@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
#||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||
  public static function loadConfigData(): array {
    $defaultValues = self::loadData();
    $folderName = config('appConfig.client_name');


    $activeModules = loadArrayFromPhpFile(base_path('app/ConfigApp/' . $folderName . '/activeModules/modules.php'));

    return self::mergedSections($defaultValues, $activeModules);
  }

#@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
#||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||
  public static function loadData(): array {
    return [
      [...self::webSettingSection()],
      [...self::latestNewsSection()],
      [...self::RealEstateData()],
      [...self::RealEstateProjects()],
      [...self::RealEstateUnits()],
      [...self::RealEstateForSale()],
      [...self::blogPostSection()],
      [...self::ProductSection()],
      [...self::FaqSection()],
      [...self::MedicalSpecialtySection()],
      [...self::OurClientSection()],
      [...self::serviceSection()],
      [...self::OurPortfolio()],
      [...self::OurBrand()],

    ];
  }


#@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
#||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||
  public static function webSettingSection(): array {
    return [
      'key' => 'webSetting',
      'title' => __('default/model-setting.section.webSetting'),
      'SettingList' => [
        'MetaTag' => [
          ...self::loadDefArr('MetaTag'),
        ],
        'DefPhoto' => [
          ...self::loadDefArr('DefPhoto'),
        ],
      ],
    ];
  }

#@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
#||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||
  public static function FaqSection(): array {
    return [
      'key' => 'Faq',
      'title' => __('default/model-setting.section.Faq'),
      'SettingList' => [
        'FaqCategory' => [
          ...self::loadDefArr('FaqCategory'),
        ],
        'FaqPost' => [
          ...self::loadDefArr('FaqPost'),
        ],
      ],
    ];
  }

#@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
#||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||
  public static function MedicalSpecialtySection(): array {
    return [
      'key' => 'MedicalSpecialty',
      'title' => __('default/model-setting.section.MedicalSpecialty'),
      'SettingList' => [
        'MedicalSpecialtyCategory' => [
          ...self::loadDefArr('MedicalSpecialtyCategory'),
        ],
        'MedicalSpecialtyPost' => [
          ...self::loadDefArr('MedicalSpecialtyPost'),
        ],
      ],
    ];
  }


#@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
#||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||
  public static function OurClientSection(): array {
    return [
      'key' => 'Faq',
      'title' => __('default/model-setting.section.OurClient'),
      'SettingList' => [
        'OurClient' => [
          ...self::loadDefArr('OurClient'),
        ],
        'OurClientProjects' => [
          ...self::loadDefArr('OurClientProjects'),
        ],
      ],
    ];
  }

#@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
#||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||
  public static function OurPortfolio(): array {
    return [
      'key' => 'OurPortfolio',
      'title' => __('default/model-setting.section.OurPortfolio'),
      'SettingList' => [
        'OurPortfolio' => [
          ...self::loadDefArr('OurPortfolio'),
        ],
      ],
    ];
  }

#@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
#||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||
  public static function OurBrand(): array {
    return [
      'key' => 'OurBrand',
      'title' => __('default/model-setting.section.OurBrand'),
      'SettingList' => [
        'OurBrand' => [
          ...self::loadDefArr('OurBrand'),
        ],
      ],
    ];
  }

#@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
#||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||
  public static function serviceSection(): array {
    return [
      'key' => 'servicePost',
      'title' => __('default/model-setting.section.servicePost'),
      'SettingList' => [
        'ServiceCategory' => [
          ...self::loadDefArr('ServiceCategory'),
        ],
        'ServicePost' => [
          ...self::loadDefArr('ServicePost'),
        ],
      ],
    ];
  }
#@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
#||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||
  public static function blogPostSection(): array {
    return [
      'key' => 'blogPost',
      'title' => __('default/model-setting.section.blogPost'),
      'SettingList' => [
        'BlogCategory' => [
          ...self::loadDefArr('BlogCategory'),
        ],
        'BlogPost' => [
          ...self::loadDefArr('BlogPost'),
        ],
      ],
    ];
  }

#@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
#||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||
  public static function latestNewsSection(): array {
    return [
      'key' => 'LatestNews',
      'title' => __('default/model-setting.section.latestNews'),
      'SettingList' => [
        'LatestNews' => [
          ...self::loadDefArr('LatestNews'),
        ],

      ],
    ];
  }

#@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
#||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||
  public static function ProductSection(): array {
    return [
      'key' => 'Product',
      'title' => __('default/model-setting.section.Product'),
      'SettingList' => [
        'ProductCategory' => [
          ...self::loadDefArr('ProductCategory'),
        ],
        'Product' => [
          ...self::loadDefArr('Product'),
        ],
      ],
    ];
  }
#@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
#||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||
  public static function RealEstateData(): array {
    return [
      'key' => 'RealEstateData',
      'title' => __('default/model-setting.section.RealEstateData'),
      'SettingList' => [
        'Developer' => [
          ...self::loadDefArr('Developer'),
        ],
        'Location' => [
          ...self::loadDefArr('Location'),
        ],
      ],
    ];
  }
#@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
#||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||
  public static function RealEstateProjects(): array {
    return [
      'key' => 'RealEstateProjects',
      'title' => __('default/model-setting.section.RealEstateProjects'),
      'SettingList' => [
        'Projects' => [
          ...self::loadDefArr('Projects'),
        ],
      ],
    ];
  }
#@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
#||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||
  public static function RealEstateUnits(): array {
    return [
      'key' => 'RealEstateUnits',
      'title' => __('default/model-setting.section.RealEstateUnits'),
      'SettingList' => [
        'Units' => [
          ...self::loadDefArr('Units'),
        ],
      ],
    ];
  }
#@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
#||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||
  public static function RealEstateForSale(): array {
    return [
      'key' => 'RealEstateForSale',
      'title' => __('default/model-setting.section.RealEstateForSale'),
      'SettingList' => [
        'ForSale' => [
          ...self::loadDefArr('ForSale'),
        ],
      ],
    ];
  }

#@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
#||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||
  public static function loadDefArr($key): array {
    return [
      'view' => false,
      'gallery' => false,
      'lang' => __('default/model-setting.columns.' . $key),
    ];
  }

#@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
#||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||
  public static function mergedSections($defaultValues, $activeModules): array {
    $mergedSections = [];

    foreach ($defaultValues as $section) {
      if (!isset($section['SettingList']) || !is_array($section['SettingList'])) {
        continue;
      }

      foreach ($section['SettingList'] as $key => $setting) {
        if (isset($activeModules[$key])) {
          // دمج الإعدادات الفعالة مع الديفولت (override للـ view/gallery)
          $section['SettingList'][$key] = array_merge($setting, $activeModules[$key]);
        }
      }
      $mergedSections[] = $section;
    }
    return $mergedSections;
  }


}
