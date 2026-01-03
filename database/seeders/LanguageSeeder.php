<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Language;
use Illuminate\Support\Facades\Schema;

class LanguageSeeder extends Seeder
{
    public function run(): void
    {
        $rtl = ['ar','fa','ur','he','yi'];

        $languages = [

            // --- A ---
            ['name' => 'Afrikaans','native_name'=>'Afrikaans','lang'=>'af'],
            ['name' => 'Albanian','native_name'=>'Shqip','lang'=>'sq'],
            ['name' => 'Amharic','native_name'=>'አማርኛ','lang'=>'am'],
            ['name' => 'Arabic','native_name'=>'العربية','lang'=>'ar'],
            ['name' => 'Armenian','native_name'=>'Հայերեն','lang'=>'hy'],
            ['name' => 'Assamese','native_name'=>'অসমীয়া','lang'=>'as'],
            ['name' => 'Azerbaijani','native_name'=>'Azərbaycanca','lang'=>'az'],

            // --- B ---
            ['name' => 'Basque','native_name'=>'Euskara','lang'=>'eu'],
            ['name' => 'Belarusian','native_name'=>'Беларуская','lang'=>'be'],
            ['name' => 'Bengali','native_name'=>'বাংলা','lang'=>'bn'],
            ['name' => 'Bosnian','native_name'=>'Bosanski','lang'=>'bs'],
            ['name' => 'Bulgarian','native_name'=>'Български','lang'=>'bg'],
            ['name' => 'Burmese','native_name'=>'မြန်မာ','lang'=>'my'],

            // --- C ---
            ['name' => 'Catalan','native_name'=>'Català','lang'=>'ca'],
            ['name' => 'Cebuano','native_name'=>'Cebuano','lang'=>'ceb'],
            ['name' => 'Chinese (Simplified)','native_name'=>'中文','lang'=>'zh'],
            ['name' => 'Chinese (Traditional)','native_name'=>'繁體中文','lang'=>'zh-TW'],
            ['name' => 'Croatian','native_name'=>'Hrvatski','lang'=>'hr'],
            ['name' => 'Czech','native_name'=>'Čeština','lang'=>'cs'],

            // --- D ---
            ['name' => 'Danish','native_name'=>'Dansk','lang'=>'da'],
            ['name' => 'Dutch','native_name'=>'Nederlands','lang'=>'nl'],

            // --- E ---
            ['name' => 'English','native_name'=>'English','lang'=>'en'],
            ['name' => 'Esperanto','native_name'=>'Esperanto','lang'=>'eo'],
            ['name' => 'Estonian','native_name'=>'Eesti','lang'=>'et'],

            // --- F ---
            ['name' => 'Filipino','native_name'=>'Filipino','lang'=>'fil'],
            ['name' => 'Finnish','native_name'=>'Suomi','lang'=>'fi'],
            ['name' => 'French','native_name'=>'Français','lang'=>'fr'],

            // --- G ---
            ['name' => 'Galician','native_name'=>'Galego','lang'=>'gl'],
            ['name' => 'Georgian','native_name'=>'ქართული','lang'=>'ka'],
            ['name' => 'German','native_name'=>'Deutsch','lang'=>'de'],
            ['name' => 'Greek','native_name'=>'Ελληνικά','lang'=>'el'],
            ['name' => 'Gujarati','native_name'=>'ગુજરાતી','lang'=>'gu'],

            // --- H ---
            ['name' => 'Haitian Creole','native_name'=>'Kreyòl Ayisyen','lang'=>'ht'],
            ['name' => 'Hausa','native_name'=>'Hausa','lang'=>'ha'],
            ['name' => 'Hebrew','native_name'=>'עברית','lang'=>'he'],
            ['name' => 'Hindi','native_name'=>'हिन्दी','lang'=>'hi'],
            ['name' => 'Hungarian','native_name'=>'Magyar','lang'=>'hu'],

            // --- I ---
            ['name' => 'Icelandic','native_name'=>'Íslenska','lang'=>'is'],
            ['name' => 'Igbo','native_name'=>'Igbo','lang'=>'ig'],
            ['name' => 'Indonesian','native_name'=>'Bahasa Indonesia','lang'=>'id'],
            ['name' => 'Irish','native_name'=>'Gaeilge','lang'=>'ga'],
            ['name' => 'Italian','native_name'=>'Italiano','lang'=>'it'],

            // --- J ---
            ['name' => 'Japanese','native_name'=>'日本語','lang'=>'ja'],
            ['name' => 'Javanese','native_name'=>'Basa Jawa','lang'=>'jv'],

            // --- K ---
            ['name' => 'Kannada','native_name'=>'ಕನ್ನಡ','lang'=>'kn'],
            ['name' => 'Kazakh','native_name'=>'Қазақша','lang'=>'kk'],
            ['name' => 'Khmer','native_name'=>'ខ្មែរ','lang'=>'km'],
            ['name' => 'Kinyarwanda','native_name'=>'Kinyarwanda','lang'=>'rw'],
            ['name' => 'Korean','native_name'=>'한국어','lang'=>'ko'],
            ['name' => 'Kurdish','native_name'=>'Kurdî','lang'=>'ku'],
            ['name' => 'Kyrgyz','native_name'=>'Кыргызча','lang'=>'ky'],

            // --- L ---
            ['name' => 'Lao','native_name'=>'ລາວ','lang'=>'lo'],
            ['name' => 'Latvian','native_name'=>'Latviešu','lang'=>'lv'],
            ['name' => 'Lithuanian','native_name'=>'Lietuvių','lang'=>'lt'],
            ['name' => 'Luxembourgish','native_name'=>'Lëtzebuergesch','lang'=>'lb'],

            // --- M ---
            ['name' => 'Macedonian','native_name'=>'Македонски','lang'=>'mk'],
            ['name' => 'Malagasy','native_name'=>'Malagasy','lang'=>'mg'],
            ['name' => 'Malay','native_name'=>'Bahasa Melayu','lang'=>'ms'],
            ['name' => 'Malayalam','native_name'=>'മലയാളം','lang'=>'ml'],
            ['name' => 'Maltese','native_name'=>'Malti','lang'=>'mt'],
            ['name' => 'Maori','native_name'=>'Māori','lang'=>'mi'],
            ['name' => 'Marathi','native_name'=>'मराठी','lang'=>'mr'],
            ['name' => 'Mongolian','native_name'=>'Монгол','lang'=>'mn'],

            // --- N ---
            ['name' => 'Nepali','native_name'=>'नेपाली','lang'=>'ne'],
            ['name' => 'Norwegian','native_name'=>'Norsk','lang'=>'no'],

            // --- O ---
            ['name' => 'Odia','native_name'=>'ଓଡ଼ିଆ','lang'=>'or'],

            // --- P ---
            ['name' => 'Pashto','native_name'=>'پښتو','lang'=>'ps'],
            ['name' => 'Persian','native_name'=>'فارسی','lang'=>'fa'],
            ['name' => 'Polish','native_name'=>'Polski','lang'=>'pl'],
            ['name' => 'Portuguese','native_name'=>'Português','lang'=>'pt'],
            ['name' => 'Punjabi','native_name'=>'ਪੰਜਾਬੀ','lang'=>'pa'],

            // --- R ---
            ['name' => 'Romanian','native_name'=>'Română','lang'=>'ro'],
            ['name' => 'Russian','native_name'=>'Русский','lang'=>'ru'],

            // --- S ---
            ['name' => 'Samoan','native_name'=>'Gagana Samoa','lang'=>'sm'],
            ['name' => 'Serbian','native_name'=>'Српски','lang'=>'sr'],
            ['name' => 'Sesotho','native_name'=>'Sesotho','lang'=>'st'],
            ['name' => 'Shona','native_name'=>'Shona','lang'=>'sn'],
            ['name' => 'Sindhi','native_name'=>'سنڌي','lang'=>'sd'],
            ['name' => 'Sinhala','native_name'=>'සිංහල','lang'=>'si'],
            ['name' => 'Slovak','native_name'=>'Slovenčina','lang'=>'sk'],
            ['name' => 'Slovenian','native_name'=>'Slovenščina','lang'=>'sl'],
            ['name' => 'Somali','native_name'=>'Soomaali','lang'=>'so'],
            ['name' => 'Spanish','native_name'=>'Español','lang'=>'es'],
            ['name' => 'Sundanese','native_name'=>'Basa Sunda','lang'=>'su'],
            ['name' => 'Swahili','native_name'=>'Kiswahili','lang'=>'sw'],
            ['name' => 'Swedish','native_name'=>'Svenska','lang'=>'sv'],

            // --- T ---
            ['name' => 'Tajik','native_name'=>'Тоҷикӣ','lang'=>'tg'],
            ['name' => 'Tamil','native_name'=>'தமிழ்','lang'=>'ta'],
            ['name' => 'Tatar','native_name'=>'Татарча','lang'=>'tt'],
            ['name' => 'Telugu','native_name'=>'తెలుగు','lang'=>'te'],
            ['name' => 'Thai','native_name'=>'ไทย','lang'=>'th'],
            ['name' => 'Turkish','native_name'=>'Türkçe','lang'=>'tr'],
            ['name' => 'Turkmen','native_name'=>'Türkmen','lang'=>'tk'],

            // --- U ---
            ['name' => 'Ukrainian','native_name'=>'Українська','lang'=>'uk'],
            ['name' => 'Urdu','native_name'=>'اردو','lang'=>'ur'],
            ['name' => 'Uyghur','native_name'=>'ئۇيغۇرچە','lang'=>'ug'],
            ['name' => 'Uzbek','native_name'=>'O‘zbekcha','lang'=>'uz'],

            // --- V ---
            ['name' => 'Vietnamese','native_name'=>'Tiếng Việt','lang'=>'vi'],

            // --- W ---
            ['name' => 'Welsh','native_name'=>'Cymraeg','lang'=>'cy'],

            // --- X ---
            ['name' => 'Xhosa','native_name'=>'isiXhosa','lang'=>'xh'],

            // --- Y ---
            ['name' => 'Yiddish','native_name'=>'יידיש','lang'=>'yi'],
            ['name' => 'Yoruba','native_name'=>'Yorùbá','lang'=>'yo'],

            // --- Z ---
            ['name' => 'Zulu','native_name'=>'isiZulu','lang'=>'zu'],

        ];

        Schema::disableForeignKeyConstraints();
        Language::truncate();
        Schema::enableForeignKeyConstraints();

        $insert = [];
        $now = now();

        foreach ($languages as $lang) {
            $insert[] = [
                'name'        => $lang['name'],
                'native_name' => $lang['native_name'],
                'code'        => $lang['lang'],
                'slug'        => $lang['lang'],
                'direction'   => in_array($lang['lang'], $rtl) ? 'rtl' : 'ltr',
                'is_default'  => $lang['lang'] === 'en',
                'is_active'   => in_array($lang['lang'], ['en', 'ta']),
                'created_at'  => $now,
                'updated_at'  => $now,
            ];
        }

        Language::insert($insert);
    }
}
