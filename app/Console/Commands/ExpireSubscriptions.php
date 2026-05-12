<?php

namespace App\Console\Commands;

use App\Models\User;
use App\Models\UserSubscription;
use Illuminate\Console\Command;
use Illuminate\Support\Carbon;

/**
 * ExpireSubscriptions
 *
 * Runs as a scheduled cron (daily) to:
 *   1. Deactivate UserSubscription rows that have passed their expires_at.
 *   2. Sync users.is_premium / users.premium_expires_at back to false/null
 *      when they no longer have any valid active subscription.
 *
 * Schedule in app/Console/Kernel.php (or bootstrap/app.php for Laravel 11):
 *
 *   $schedule->command('subscriptions:expire')->daily();
 *
 * Or register in routes/console.php:
 *
 *   Schedule::command('subscriptions:expire')->daily();
 */
class ExpireSubscriptions extends Command
{
    protected $signature   = 'subscriptions:expire';
    protected $description = 'Deactivate expired subscriptions and sync user premium flags';

    public function handle(): int
    {
        $now = Carbon::now();

        // ── Step 1: Deactivate subscription rows past expires_at ──────────
        $expiredCount = UserSubscription::where('is_active', true)
            ->where('payment_status', 'completed')
            ->where('expires_at', '<', $now)
            ->update(['is_active' => false]);

        $this->info("Deactivated {$expiredCount} expired subscription(s).");

        // ── Step 2: Find users whose is_premium=true but have no valid sub ─
        // A valid subscription: is_active=true, payment_status=completed, expires_at > now
        $staleUsers = User::where('is_premium', true)
            ->whereDoesntHave('activeSubscription')
            ->get(['id', 'name', 'email']);

        $syncCount = 0;
        foreach ($staleUsers as $user) {
            $user->update([
                'is_premium'         => false,
                'premium_expires_at' => null,
            ]);
            $syncCount++;
            $this->line("  → Cleared premium flag for user #{$user->id} ({$user->email})");
        }

        $this->info("Synced premium flag for {$syncCount} user(s).");
        $this->info('Done.');

        return self::SUCCESS;
    }
}