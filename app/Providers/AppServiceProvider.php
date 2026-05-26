<?php

namespace App\Providers;

use Illuminate\Support\Facades\Config;
use Illuminate\Support\ServiceProvider;
use App\Models\SystemSetting;
use Illuminate\Database\QueryException;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        try {
            // Load all system settings into config with caching
            $settings = \Illuminate\Support\Facades\Cache::rememberForever('system_settings', function () {
                return SystemSetting::all()->pluck('value', 'key');
            });

            foreach ($settings as $key => $value) {
                Config::set($key, $value);
            }

            // Explicitly configure settings if present
            if ($settings->isNotEmpty()) {
                $this->configureMailSettings($settings);
                $this->configureStripeSettings($settings);
                $this->configureAwsSettings($settings);
                $this->configureJwtSettings($settings);
                $this->configureTwilioSettings($settings);
                $this->configureEkpaySettings($settings);
            }

            // Always configure session cookie environment settings
            $this->configureSessionSettings($settings);

            // Configure Allowed Origins (CORS)
            $this->configureCorsSettings();

        } catch (QueryException $e) {
            \Log::error('Error loading system settings: ' . $e->getMessage());
        } catch (\Exception $e) {
            \Log::error('Unexpected error loading system settings: ' . $e->getMessage());
        }

        // Configure Allowed Origins (CORS)
        $this->configureCorsSettings();
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        // Register any application services.
    }

    /**
     * Configure mail settings dynamically.
     *
     * @param \Illuminate\Support\Collection $settings
     * @return void
     */
    protected function configureMailSettings($settings)
    {
        $mailer = $settings->get('MAIL_MAILER', 'smtp');
        Config::set('mail.default', $mailer);

        if ($mailer === 'smtp') {
            Config::set('mail.mailers.smtp.host', $settings->get('MAIL_HOST'));
            Config::set('mail.mailers.smtp.port', $settings->get('MAIL_PORT'));
            Config::set('mail.mailers.smtp.username', $settings->get('MAIL_USERNAME'));
            Config::set('mail.mailers.smtp.password', $settings->get('MAIL_PASSWORD'));
            Config::set('mail.mailers.smtp.encryption', $settings->get('MAIL_ENCRYPTION'));
        }

        Config::set('mail.from.address', $settings->get('MAIL_FROM_ADDRESS'));
        Config::set('mail.from.name', $settings->get('MAIL_FROM_NAME'));
    }

    /**
     * Configure Stripe settings dynamically.
     *
     * @param \Illuminate\Support\Collection $settings
     * @return void
     */
    protected function configureStripeSettings($settings)
    {
        Config::set('services.stripe.key', $settings->get('STRIPE_KEY'));
        Config::set('services.stripe.secret', $settings->get('STRIPE_SECRET'));
        Config::set('services.stripe.webhook', $settings->get('STRIPE_WEBHOOK_SECRET'));
    }

    /**
     * Configure AWS settings dynamically.
     *
     * @param \Illuminate\Support\Collection $settings
     * @return void
     */
    protected function configureAwsSettings($settings)
    {
        Config::set('filesystems.disks.s3.key', $settings->get('AWS_ACCESS_KEY_ID'));
        Config::set('filesystems.disks.s3.secret', $settings->get('AWS_SECRET_ACCESS_KEY'));
        Config::set('filesystems.disks.s3.region', $settings->get('AWS_DEFAULT_REGION'));
        Config::set('filesystems.disks.s3.bucket', $settings->get('AWS_BUCKET'));
        Config::set('filesystems.disks.s3.use_path_style_endpoint', filter_var($settings->get('AWS_USE_PATH_STYLE_ENDPOINT', false), FILTER_VALIDATE_BOOLEAN));
        // Note: AWS_FILE_LOAD_BASE is often used in custom logic, usually corresponding to filesystems.disks.s3.url or similar
        if ($settings->has('AWS_FILE_LOAD_BASE')) {
            Config::set('filesystems.disks.s3.url', $settings->get('AWS_FILE_LOAD_BASE'));
        }
    }

    /**
     * Configure JWT settings dynamically.
     *
     * @param \Illuminate\Support\Collection $settings
     * @return void
     */
    protected function configureJwtSettings($settings)
    {
        if ($settings->has('JWT_TTL')) {
            Config::set('jwt.ttl', (int) $settings->get('JWT_TTL'));
        }
        if ($settings->has('JWT_REFRESH_TTL')) {
            Config::set('jwt.refresh_ttl', (int) $settings->get('JWT_REFRESH_TTL'));
        }
        if ($settings->has('JWT_BLACKLIST_ENABLED')) {
            Config::set('jwt.blacklist_enabled', filter_var($settings->get('JWT_BLACKLIST_ENABLED'), FILTER_VALIDATE_BOOLEAN));
        }
    }

    /**
     * Configure Twilio settings dynamically.
     *
     * @param \Illuminate\Support\Collection $settings
     * @return void
     */
    protected function configureTwilioSettings($settings)
    {
        Config::set('services.twilio.sid', $settings->get('TWILIO_SID'));
        Config::set('services.twilio.token', $settings->get('TWILIO_AUTH_TOKEN'));
        Config::set('services.twilio.from', $settings->get('TWILIO_PHONE_NUMBER'));
    }

    /**
     * Configure CORS settings from database.
     */
    protected function configureCorsSettings()
    {
        try {
            $origins = \Illuminate\Support\Facades\Cache::rememberForever('allowed_origins', function () {
                return \App\Models\AllowedOrigin::pluck('origin_url')->toArray();
            });

            // Always allow local development origins
            $localOrigins = [
                'http://localhost:3000',
                'http://localhost:3001',
                'http://127.0.0.1:3000',
                'http://127.0.0.1:3001',
            ];

            $allOrigins = array_unique(array_merge($origins, $localOrigins));

            Config::set('cors.allowed_origins', $allOrigins);
            Config::set('cors.supports_credentials', true);
            Config::set('cors.allowed_methods', ['*']);
            Config::set('cors.allowed_headers', ['*']);
        } catch (\Exception $e) {
            \Log::error('Error loading allowed origins: ' . $e->getMessage());
        }
    }

    /**
     * Configure Ekpay settings dynamically.
     *
     * @param \Illuminate\Support\Collection $settings
     * @return void
     */
    protected function configureEkpaySettings($settings)
    {
        if ($settings->has('AKPAY_MER_REG_ID')) {
            Config::set('services.ekpay.mer_reg_id', $settings->get('AKPAY_MER_REG_ID'));
        }
        if ($settings->has('AKPAY_MER_PASS_KEY')) {
            Config::set('services.ekpay.mer_pass_key', $settings->get('AKPAY_MER_PASS_KEY'));
        }
        if ($settings->has('AKPAY_API_URL')) {
            Config::set('services.ekpay.api_url', $settings->get('AKPAY_API_URL'));
        }
        if ($settings->has('AKPAY_IPN_URL')) {
            Config::set('services.ekpay.ipn_url', $settings->get('AKPAY_IPN_URL'));
        }
        if ($settings->has('WHITE_LIST_IP')) {
            Config::set('services.ekpay.whitelist_ip', $settings->get('WHITE_LIST_IP'));
        }
    }

    /**
     * Configure Session/Cookie settings dynamically based on deployment environment.
     *
     * @param \Illuminate\Support\Collection $settings
     * @return void
     */
    protected function configureSessionSettings($settings)
    {
        $cookieEnvType = $settings->get('COOKIE_ENVIRONMENT_TYPE', 'same_domain');
        $cookieDomainBase = $settings->get('COOKIE_DOMAIN_BASE', null);

        // --- Deployment-type presets ---
        if ($cookieEnvType === 'different_domain') {
            // Cross-origin: SameSite=None MUST have Secure=true per browser spec
            Config::set('session.secure', true);
            Config::set('session.same_site', 'none');
            Config::set('session.domain', null);
        } elseif ($cookieEnvType === 'sub_domain') {
            // Sub-domain SSO: cookie shared across *.domain.com
            if ($cookieDomainBase && !str_starts_with($cookieDomainBase, '.')) {
                $cookieDomainBase = '.' . $cookieDomainBase;
            }
            Config::set('session.secure', env('SESSION_SECURE_COOKIE', request()->secure()));
            Config::set('session.same_site', 'lax');
            Config::set('session.domain', $cookieDomainBase);
        } else { // same_domain (default)
            Config::set('session.secure', env('SESSION_SECURE_COOKIE', request()->secure()));
            Config::set('session.same_site', 'lax');
            Config::set('session.domain', null);
        }

        // --- Individual overrides (win over preset if set) ---

        // Cookie Name
        if ($settings->has('COOKIE_NAME')) {
            Config::set('session.cookie', $settings->get('COOKIE_NAME'));
        }

        // Cookie Lifetime (minutes)
        if ($settings->has('COOKIE_LIFETIME')) {
            Config::set('session.lifetime', (int) $settings->get('COOKIE_LIFETIME', 120));
        }

        // HttpOnly flag
        if ($settings->has('COOKIE_HTTP_ONLY')) {
            Config::set('session.http_only', filter_var($settings->get('COOKIE_HTTP_ONLY', 'true'), FILTER_VALIDATE_BOOLEAN));
        }

        // Secure flag manual override
        if ($settings->has('COOKIE_SECURE')) {
            Config::set('session.secure', filter_var($settings->get('COOKIE_SECURE'), FILTER_VALIDATE_BOOLEAN));
        }

        // SameSite manual override
        if ($settings->has('COOKIE_SAME_SITE')) {
            $sameSite = strtolower($settings->get('COOKIE_SAME_SITE', 'lax'));
            // SameSite=none requires Secure=true per browser spec
            if ($sameSite === 'none') {
                Config::set('session.secure', true);
            }
            Config::set('session.same_site', $sameSite);
        }

        // Cookie Path
        if ($settings->has('COOKIE_PATH')) {
            Config::set('session.path', $settings->get('COOKIE_PATH', '/'));
        }

        // Expire on browser close
        if ($settings->has('COOKIE_EXPIRE_ON_CLOSE')) {
            Config::set('session.expire_on_close', filter_var($settings->get('COOKIE_EXPIRE_ON_CLOSE', 'false'), FILTER_VALIDATE_BOOLEAN));
        }

        // JWT Cookie TTL override (minutes)
        if ($settings->has('JWT_COOKIE_TTL')) {
            Config::set('jwt.cookie_ttl', (int) $settings->get('JWT_COOKIE_TTL'));
        }
    }
}
