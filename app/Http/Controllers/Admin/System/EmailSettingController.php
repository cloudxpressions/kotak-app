<?php

namespace App\Http\Controllers\Admin\System;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\DB;
use App\Mail\TestMail;

class EmailSettingController extends Controller implements HasMiddleware
{
    /**
     * Get the middleware that should be assigned to the controller.
     */
    public static function middleware(): array
    {
        return [
            new Middleware('permission:email-settings.view', only: ['index']),
            new Middleware('permission:email-settings.update', only: ['update']),
            new Middleware('permission:email-settings.test', only: ['sendTestMail']),
        ];
    }

    /**
     * Display the email settings form.
     */
    public function index()
    {
        $mailConfig = [
            'MAIL_MAILER'        => env('MAIL_MAILER'),
            'MAIL_HOST'          => env('MAIL_HOST'),
            'MAIL_PORT'          => env('MAIL_PORT'),
            'MAIL_USERNAME'      => env('MAIL_USERNAME'),
            'MAIL_PASSWORD'      => env('MAIL_PASSWORD'),
            'MAIL_ENCRYPTION'    => env('MAIL_ENCRYPTION'),
            'MAIL_FROM_ADDRESS'  => env('MAIL_FROM_ADDRESS'),
            'MAIL_FROM_NAME'     => env('MAIL_FROM_NAME'),
            'MAIL_API_KEY'       => env('MAIL_API_KEY'),
            'MAIL_ACTIVE_STATUS' => env('MAIL_ACTIVE_STATUS', '0'),
        ];

        return view('admin.system.email-settings.index', compact('mailConfig'));
    }

    /**
     * Update email settings in .env file
     */
    public function update(Request $request)
    {
        $validated = $request->validate([
            'MAIL_FROM_NAME'     => 'required|string|max:255',
            'MAIL_FROM_ADDRESS'  => 'required|email|max:255',
            'MAIL_MAILER'        => 'required|string|in:smtp,sendmail,mailgun,ses,postmark,log,array,other',
            'MAIL_HOST'          => 'nullable|string|max:255',
            'MAIL_PORT'          => 'nullable|integer',
            'MAIL_USERNAME'      => 'nullable|string|max:255',
            'MAIL_PASSWORD'      => 'nullable|string|max:255',
            'MAIL_ENCRYPTION'    => 'nullable|string|in:tls,ssl,null',
            'MAIL_API_KEY'       => 'nullable|string|max:255',
            'MAIL_ACTIVE_STATUS' => 'required|boolean',
        ]);

        DB::beginTransaction();

        try {
            foreach ($validated as $key => $value) {
                $this->setEnvironmentValue($key, $value);
            }

            Artisan::call('config:clear');
            DB::commit();

            return redirect()->route('admin.system.email-settings.index')->with('success', 'Email settings updated successfully.');
        } catch (\Throwable $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Failed to update email settings: ' . $e->getMessage());
        }
    }

    /**
     * Send a test email
     */
    public function sendTestMail(Request $request)
    {
        $request->validate([
            'to_mail'     => 'required|email',
            'mail_engine' => 'required|string|in:smtp,sendmail,mailgun,ses,postmark,log,array,other',
        ]);

        // Update the mail configuration for this request
        config(['mail.default' => $request->mail_engine]);
        config(['mail.mailers.' . $request->mail_engine => [
            'transport' => $request->mail_engine,
            'host' => env('MAIL_HOST'),
            'port' => env('MAIL_PORT'),
            'encryption' => env('MAIL_ENCRYPTION'),
            'username' => env('MAIL_USERNAME'),
            'password' => env('MAIL_PASSWORD'),
            'timeout' => null,
            'auth_mode' => null,
        ]]);

        try {
            Mail::to($request->to_mail)->send(new TestMail());
            return response()->json(['status' => 'success', 'message' => 'Test email sent successfully!']);
        } catch (\Throwable $e) {
            return response()->json(['status' => 'error', 'message' => 'Failed to send test email: ' . $e->getMessage()]);
        }
    }

    /**
     * Helper method to update environment variables
     */
    private function setEnvironmentValue(string $key, $value): void
    {
        $path = base_path('.env');

        if (!file_exists($path)) return;

        $content = file_get_contents($path);

        $escapedValue = (is_string($value) && str_contains($value, ' ')) ? "\"{$value}\"" : $value;

        if (preg_match("/^{$key}=.*/m", $content)) {
            $content = preg_replace("/^{$key}=.*/m", "{$key}={$escapedValue}", $content);
        } else {
            $content .= "\n{$key}={$escapedValue}";
        }

        file_put_contents($path, $content);
    }
}