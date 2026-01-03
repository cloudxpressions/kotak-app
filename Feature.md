Nice, let’s lock these in.
Here are **final, cleaned-up Laravel migrations** for all three: **maintenances**, **blocked_ips**, and **user_sessions**, including the extra fields we discussed that make them more “industry standard”.

You can split these into three separate migration files.

---

## 1️⃣ `maintenances` table

```php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('maintenances', function (Blueprint $table) {
            $table->id();

            // Core switch
            $table->boolean('maintenance_mode')
                ->default(false)
                ->comment('If true, application is in maintenance mode');

            // Public-facing content
            $table->string('title')->nullable()
                ->comment('Heading for the maintenance page');
            $table->text('subtitle')->nullable()
                ->comment('Detailed message / instructions for users');
            $table->string('maintenance_page_banner')->nullable()
                ->comment('Optional image/banner path for maintenance screen');

            // Optional scheduling
            $table->timestamp('starts_at')->nullable()
                ->comment('Planned start time for maintenance');
            $table->timestamp('ends_at')->nullable()
                ->comment('Planned end time for maintenance');

            // Optional allowlist
            $table->json('allowed_ips')->nullable()
                ->comment('IP addresses allowed to bypass maintenance');

            // Optional flag for unplanned outage
            $table->boolean('is_emergency')
                ->default(false)
                ->comment('True for unplanned/emergency downtime');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('maintenances');
    }
};
```

---

## 2️⃣ `blocked_ips` table

```php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('blocked_ips', function (Blueprint $table) {
            $table->id();

            $table->string('ip_address', 45)
                ->unique()
                ->comment('IPv4 or IPv6 string of blocked IP');

            $table->text('reason')->nullable()
                ->comment('Reason for blocking this IP (abuse, brute force, spam, etc.)');

            // Block duration
            $table->timestamp('blocked_until')->nullable()
                ->comment('If set, IP is blocked until this time');
            $table->boolean('is_permanent')
                ->default(false)
                ->comment('True if block is permanent');

            // Optional forensic info / tracking
            $table->unsignedInteger('attempts_count')
                ->default(0)
                ->comment('Number of suspicious attempts recorded');
            $table->timestamp('last_attempt_at')->nullable()
                ->comment('Last time this IP was seen making a bad request');
            $table->text('user_agent')->nullable()
                ->comment('Example user agent seen from this IP');

            // Who blocked it (optional, tie to admins)
            $table->unsignedBigInteger('created_by')->nullable()
                ->comment('Admin who added this block');
            $table->unsignedBigInteger('updated_by')->nullable()
                ->comment('Admin who last updated this block');

            $table->timestamps();

            $table->index('ip_address');

            // Foreign keys (optional: uncomment if you have an admins table)
            // $table->foreign('created_by')->references('id')->on('admins')->nullOnDelete();
            // $table->foreign('updated_by')->references('id')->on('admins')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('blocked_ips');
    }
};
```

---

## 3️⃣ `user_sessions` table (general platform sessions)

```php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('user_sessions', function (Blueprint $table) {
            $table->id();

            // Link to user
            $table->foreignId('user_id')
                ->constrained()
                ->onDelete('cascade');

            // Core session identity
            $table->string('session_token', 64)
                ->unique()
                ->comment('Random token identifying this session (e.g., JWT id / session id)');
            $table->boolean('is_active')
                ->default(true)
                ->comment('True while the session is active');

            // Device / client info
            $table->string('ip_address', 45)->nullable()
                ->comment('IP used for this session');
            $table->text('user_agent')->nullable()
                ->comment('Raw user agent string');
            $table->string('device')->nullable()
                ->comment('Device name/type if parsed (mobile, desktop, etc.)');
            $table->string('browser')->nullable()
                ->comment('Browser name/version if parsed');
            $table->string('platform')->nullable()
                ->comment('OS/platform (Windows, Android, iOS, etc.)');

            // Location (optional, if you resolve via geo IP)
            $table->string('country', 2)->nullable()
                ->comment('ISO country code if available');
            $table->string('region')->nullable()
                ->comment('State/region if available');
            $table->string('city')->nullable()
                ->comment('City if available');

            // Timing
            $table->timestamp('login_at')
                ->comment('When the session started');
            $table->timestamp('logout_at')->nullable()
                ->comment('When the session ended (if ended)');
            $table->timestamp('last_seen_at')->nullable()
                ->comment('Last activity ping from this session');

            // Type & revocation
            $table->string('session_type', 20)
                ->default('web')
                ->comment('Session category: web, mobile, api, admin, etc.');
            $table->timestamp('revoked_at')->nullable()
                ->comment('When the session was forcefully revoked');
            $table->string('revoked_reason')->nullable()
                ->comment('Reason for revoking (security issue, admin logout, etc.)');

            $table->timestamps();

            $table->index(['user_id', 'is_active']);
            $table->index('login_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('user_sessions');
    }
};
```

---

If you want, next step I can:

* Write a **short README** describing how each of these tables is used in the app (maintenance middleware, IP guard, session guard), or
* Sketch **middleware logic** for:

  * checking `maintenances` table
  * enforcing `blocked_ips`
  * recording `user_sessions` on login/logout.
