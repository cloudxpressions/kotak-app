<?php

namespace Database\Seeders;

use App\Models\LegalPage;
use Illuminate\Database\Seeder;

class LegalPageSeeder extends Seeder
{
    /**
     * Seed the legal_pages table with baseline documents.
     */
    public function run(): void
    {
        $pages = [
            [
                'title' => 'Privacy Policy',
                'slug' => 'privacy-policy',
                'seo_title' => 'Privacy Policy - Kalviplus',
                'seo_description' => 'Learn how Kalviplus collects, stores, and secures learner data.',
                'seo_keywords' => 'privacy policy, data protection, kalviplus',
                'content' => <<<HTML
<h1>Privacy Policy</h1>
<p><strong>Updated:</strong> December 2025</p>
<p>Kalviplus (“Company”, “we”, “us”, “our”) provides a learning platform for competitive exams. This Privacy Policy describes what data we collect, why we collect it, and the choices you have in relation to your information.</p>

<h2>Information We Collect</h2>
<ul>
    <li><strong>Account Data:</strong> Name, email address, mobile number, password hash, country, and any profile details you intentionally share.</li>
    <li><strong>Learning Activity:</strong> Purchased bundles, completed lessons, mock tests, notes, progress, device/IP metadata, and error logs.</li>
    <li><strong>Payment Records:</strong> Transaction identifiers, invoice value, and payment channel metadata (payments are processed by Razorpay; we never store card numbers).</li>
    <li><strong>Support Interactions:</strong> Tickets, emails, attachments, and feedback submitted to our team.</li>
</ul>

<h2>How We Use Data</h2>
<ul>
    <li>Authenticate you and keep the session secure.</li>
    <li>Deliver personalized study recommendations and reminders.</li>
    <li>Process orders, send tax invoices, and handle refunds when eligible.</li>
    <li>Improve our product through analytics and aggregated insights.</li>
    <li>Meet legal, accounting, and fraud-prevention obligations.</li>
</ul>

<h2>Sharing & Disclosure</h2>
<p>We never sell learner data. Limited data may be shared with:</p>
<ul>
    <li><strong>Payment partners</strong> (Razorpay, bank gateways) to process your orders.</li>
    <li><strong>Infrastructure partners</strong> (cloud hosting, CDN, storage) under strict DPAs.</li>
    <li><strong>Law enforcement</strong> when mandated by applicable laws.</li>
</ul>

<h2>Data Retention & Deletion</h2>
<p>We retain data for as long as your account remains active or longer if laws require us to keep billing records. You may raise a deletion request through the in-app “Delete Account” option or by emailing <a href="mailto:support@kalviplus.com">support@kalviplus.com</a>. Most records are removed within 14 business days, except invoices that must be preserved for compliance.</p>

<h2>Your Rights</h2>
<ul>
    <li>Access the information we store about you.</li>
    <li>Request correction of inaccurate or outdated data.</li>
    <li>Withdraw consent for marketing communications.</li>
    <li>Request deletion of your account and associated learning history.</li>
</ul>

<h2>Contact</h2>
<p>Email: <a href="mailto:support@kalviplus.com">support@kalviplus.com</a><br/>Kalviplus, Chennai, Tamil Nadu, India</p>
HTML,
            ],
            [
                'title' => 'Terms of Service',
                'slug' => 'terms-of-service',
                'seo_title' => 'Terms of Service - Kalviplus',
                'seo_description' => 'Service terms governing the Kalviplus learning platform.',
                'seo_keywords' => 'terms of service, kalviplus, user agreement',
                'content' => <<<HTML
<h1>Terms of Service</h1>
<p><strong>Effective:</strong> December 2025</p>
<p>These Terms of Service (“Terms”) outline your obligations when using Kalviplus. By creating an account or accessing our content you agree to these Terms.</p>

<h2>1. Account Responsibilities</h2>
<ul>
    <li>You must provide accurate information and keep credentials secure.</li>
    <li>You are responsible for all activity carried out through your account or API token.</li>
    <li>We may suspend or terminate accounts involved in abuse, sharing, or piracy.</li>
</ul>

<h2>2. License to Use Content</h2>
<p>Kalviplus grants you a personal, non-transferable license to view purchased notes, question banks, and recordings. You must not reproduce, sell, or publicly share our intellectual property without written consent.</p>

<h2>3. Payments & Refunds</h2>
<ul>
    <li>Payments are processed securely via Razorpay or other notified gateways.</li>
    <li>Access to digital content is delivered immediately post-payment.</li>
    <li>Refunds are available only when content is unavailable, incomplete, or materially different from what was promised.</li>
</ul>

<h2>4. Acceptable Use</h2>
<p>You agree not to upload malware, scrape data, abuse discussion spaces, or attempt to bypass our security controls. Sharing screenshots, downloadable files, or login sessions outside your household is strictly prohibited.</p>

<h2>5. Termination</h2>
<p>We reserve the right to disable access without refund for violations of these Terms, suspected fraud, or requests from judicial authorities.</p>

<h2>6. Disclaimer & Limitation of Liability</h2>
<p>Kalviplus content is provided “as is”. We do not guarantee rank outcomes or exam results. We are not liable for indirect damages, data loss, or service interruptions beyond our reasonable control.</p>

<h2>7. Changes to Terms</h2>
<p>We may update these Terms periodically. We will notify users through in-app banners or email. Continued use of the platform after updates constitutes acceptance of the revised Terms.</p>
HTML,
            ],
            [
                'title' => 'Refund Policy',
                'slug' => 'refund-policy',
                'seo_title' => 'Refund Policy - Kalviplus',
                'seo_description' => 'Understand when and how Kalviplus can issue refunds.',
                'seo_keywords' => 'refund policy, payment, kalviplus',
                'content' => <<<HTML
<h1>Refund Policy</h1>
<p><strong>Updated:</strong> December 2025</p>
<p>Digital education products are instantly accessible, therefore refunds are limited and assessed on objective criteria.</p>

<h2>Eligible Scenarios</h2>
<ul>
    <li>You paid for a product that was never delivered due to a system issue.</li>
    <li>The purchased bundle is materially different from its description or suffers from critical defects we cannot resolve.</li>
    <li>You were charged more than once for the same order (duplicate payment).</li>
</ul>

<h2>Not Eligible</h2>
<ul>
    <li>Change of mind or switching to a different goal.</li>
    <li>Completed or partially consumed mock tests/courses.</li>
    <li>Account suspension due to policy violations.</li>
</ul>

<h2>Request Workflow</h2>
<ol>
    <li>Email <a href="mailto:support@kalviplus.com">support@kalviplus.com</a> within 3 working days of purchase.</li>
    <li>Include registered email, payment ID, and screenshots or a short explanation.</li>
    <li>We investigate within five business days and communicate the outcome.</li>
</ol>

<p>Approved refunds are processed back to the original payment source within 7–10 working days. Bank timelines may vary.</p>
HTML,
            ],
            [
                'title' => 'Cookie Policy',
                'slug' => 'cookie-policy',
                'seo_title' => 'Cookie Policy - Kalviplus',
                'seo_description' => 'Details about cookies and other local storage used by Kalviplus.',
                'seo_keywords' => 'cookie policy, cookies, kalviplus',
                'content' => <<<HTML
<h1>Cookie & Local Storage Policy</h1>
<p><strong>Updated:</strong> December 2025</p>
<p>Cookies help us provide a reliable student experience. We keep them minimal and transparent.</p>

<h2>Types of Cookies</h2>
<ul>
    <li><strong>Essential Cookies:</strong> Maintain sessions, load dashboards, and keep CSRF protection intact.</li>
    <li><strong>Preference Cookies:</strong> Remember selected language, theme, and recently viewed courses.</li>
    <li><strong>Analytics Cookies:</strong> Aggregate anonymous usage statistics to identify broken flows.</li>
</ul>

<p>We do not run third-party advertising pixels on the learning portal.</p>

<h2>Managing Cookies</h2>
<p>You may clear or block cookies through the browser. Doing so may log you out frequently or break certain modules such as mock test timers.</p>
HTML,
            ],
            [
                'title' => 'Data Deletion Policy',
                'slug' => 'data-deletion-policy',
                'seo_title' => 'Data Deletion & Retention Policy - Kalviplus',
                'seo_description' => 'How Kalviplus processes account deletion and retention obligations.',
                'seo_keywords' => 'data deletion, retention, kalviplus',
                'content' => <<<HTML
<h1>Data Deletion Policy</h1>
<p><strong>Updated:</strong> December 2025</p>
<p>You control your profile data and can request deletion at any time.</p>

<h2>How to Initiate Deletion</h2>
<ul>
    <li>Navigate to <em>Account Settings → Delete Account</em> inside the app; or</li>
    <li>Email <a href="mailto:support@kalviplus.com">support@kalviplus.com</a> from your registered email address.</li>
</ul>

<h2>What Happens Next</h2>
<ol>
    <li>We acknowledge the request and verify ownership.</li>
    <li>Account, profile, wishlist, and learning data are purged from active databases.</li>
    <li>Backups containing your data are overwritten on their normal rotation schedule (up to 30 days).</li>
</ol>

<h2>Data We May Retain</h2>
<p>Invoice line items, tax records, compliance logs, and dispute notes may be retained for up to eight years as mandated by financial regulations.</p>

<h2>Timelines</h2>
<p>Deletion requests are typically completed within 7–14 business days depending on verification and regulatory reviews.</p>
HTML,
            ],
        ];

        foreach ($pages as $page) {
            LegalPage::updateOrCreate(
                ['slug' => $page['slug']],
                [
                    'title' => $page['title'],
                    'content' => $page['content'],
                    'is_active' => true,
                    'seo_title' => $page['seo_title'],
                    'seo_description' => $page['seo_description'],
                    'seo_keywords' => $page['seo_keywords'],
                ]
            );
        }
    }
}
