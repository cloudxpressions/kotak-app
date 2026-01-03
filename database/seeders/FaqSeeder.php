<?php

namespace Database\Seeders;

use App\Models\Faq;
use App\Models\Language;
use Illuminate\Database\Seeder;

class FaqSeeder extends Seeder
{
    public function run(): void
    {
        $englishId = Language::where('code', 'en')->first()?->id ?? 1;
        $tamilId = Language::where('code', 'ta')->first()?->id;

        $faqs = [
            [
                'sort_order' => 1,
                'is_featured' => true,
                'is_active' => true,
                'translations' => [
                    [
                        'language_id' => $englishId,
                        'category' => 'Getting Started',
                        'question' => 'What exams does Kalviplus currently support?',
                        'answer' => 'We cover TNPSC (Group 1, 2, 4), SSC (CGL, CHSL), UPSC prelim topics, and major banking exams. Fresh goal-specific modules are added every quarter.',
                    ],
                    [
                        'language_id' => $tamilId,
                        'category' => 'தொடங்குதல்',
                        'question' => 'கல்விபிளஸ் தற்போது எந்த தேர்வுகளை ஆதரிக்கிறது?',
                        'answer' => 'நாங்கள் TNPSC (குழு 1, 2, 4), SSC (CGL, CHSL), UPSC பூர்வாங்க தலைப்புகள் மற்றும் முக்கிய வங்கி தேர்வுகளை உள்ளடக்குகிறோம். புதிய நோக்கங்களை நோக்கி முழு மாதிரிகளில் தொடக்கப்பணி செய்கிறோம்.',
                    ],
                ],
            ],
            [
                'sort_order' => 2,
                'is_featured' => true,
                'is_active' => true,
                'translations' => [
                    [
                        'language_id' => $englishId,
                        'category' => 'Payments & Pricing',
                        'question' => 'Can I access my purchases on multiple devices?',
                        'answer' => 'Yes. You can log in on up to three personal devices. When a fourth device logs in we ask for an OTP challenge and terminate the oldest session.',
                    ],
                    [
                        'language_id' => $tamilId,
                        'category' => 'கட்டணங்கள் மற்றும் விலை',
                        'question' => 'நான் வாங்கியவற்றை பல சாதனங்களில் பயன்படுத்த முடியுமா?',
                        'answer' => 'ஆம், நீங்கள் முதல் 3 சாதனங்களில் உள்நுழையலாம். நான்காவது சாதனம் முயற்சி செய்தால் ஒருமுறை OTP கேட்டு பழைய அமர்வை நிறுத்துகிறோம்.',
                    ],
                ],
            ],
            [
                'sort_order' => 3,
                'is_featured' => false,
                'is_active' => true,
                'translations' => [
                    [
                        'language_id' => $englishId,
                        'category' => 'Payments & Pricing',
                        'question' => 'Do you provide EMI or installment options?',
                        'answer' => 'For bundles priced above 4,999 INR we support Razorpay EMI and UPI AutoPay plans. Open the Easy Pay tab on the checkout page to view available offers.',
                    ],
                    [
                        'language_id' => $tamilId,
                        'category' => 'கட்டணங்கள் மற்றும் விலை',
                        'question' => 'EMI அல்லது தவணை திட்டங்கள் உள்ளதா?',
                        'answer' => '₹4,999 மீதமான பண்டல்களுக்கு Razorpay EMI மற்றும் UPI AutoPay திட்டங்கள் உள்ளன. செலவுசெய்யும் பக்கத்தில் Easy Pay பட்டை திறந்து விவரங்களைப் பார்க்கலாம்.',
                    ],
                ],
            ],
            [
                'sort_order' => 4,
                'is_featured' => false,
                'is_active' => true,
                'translations' => [
                    [
                        'language_id' => $englishId,
                        'category' => 'Learning Experience',
                        'question' => 'How often are notes and mock tests updated?',
                        'answer' => 'Micro notes are refreshed every month and test series follow each official notification cycle. We also publish Rapid Revision capsules one week before every major exam.',
                    ],
                    [
                        'language_id' => $tamilId,
                        'category' => 'கற்றல் அனுபவம்',
                        'question' => 'குறிப்பு மற்றும் மொக் டெஸ்ட்கள் எவ்வளவு முறையில் புதுப்பிக்கப்படுகின்றன?',
                        'answer' => 'மைக்ரோ நோட்ஸ் மாதத்திற்கு ஒரு முறையும், மொக் டெஸ்ட்கள் அதிகாரப்பூர்வ அறிவிப்புகளுக்கு ஏற்ப புதுப்பிக்கப்படுகின்றன. முக்கிய தேர்வு முன் Rapid Revision தொகுப்புகளை நாளில் வருகிறோம்.',
                    ],
                ],
            ],
            [
                'sort_order' => 5,
                'is_featured' => true,
                'is_active' => true,
                'translations' => [
                    [
                        'language_id' => $englishId,
                        'category' => 'Support',
                        'question' => 'How can I talk to a mentor if I get stuck?',
                        'answer' => 'Inside the app, tap Help > Ask Expert to create a thread. Mentors respond within 24 hours on working days. For urgent issues email support@kalviplus.com.',
                    ],
                    [
                        'language_id' => $tamilId,
                        'category' => 'ஆதரவு',
                        'question' => 'பிரச்சினை நேர்ந்தால் வழிகாட்டியுடன் எப்படி தொடர்புகொள்வது?',
                        'answer' => 'ஆப்பில் Help → Ask Expert என்பதைத் தொடுக. வழிகாட்டிகள் நாளொன்று வேலைநாள்களில் 24 மணிக் கடத்தியுள்ளம். அவசரங்கள் support@kalviplus.com என்ற மின்னஞ்சலில் தெரிவிக்கவும்.',
                    ],
                ],
            ],
        ];

        foreach ($faqs as $faqData) {
            $translations = $faqData['translations'];
            unset($faqData['translations']);

            $faq = Faq::create($faqData);

            foreach ($translations as $translation) {
                if ($translation['language_id']) {
                    $faq->translations()->create($translation);
                }
            }
        }
    }
}
