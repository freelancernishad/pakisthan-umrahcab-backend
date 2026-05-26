<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\SystemSetting;

class AdminDashboardController extends Controller
{
    public function index(Request $request)
    {
        $systemInfo = [
            'client_ip' => $request->ip(),
            'server_ip' => gethostbyname(gethostname()),
            'php_version' => PHP_VERSION,
            'laravel_version' => app()->version(),
            'server_os' => PHP_OS,
            'server_software' => $_SERVER['SERVER_SOFTWARE'] ?? 'N/A',
            'database_connection' => \DB::connection()->getDatabaseName() ? 'Connected' : 'Disconnected',
            'hostname' => gethostname(),
            'os_full' => php_uname(),
            'kernel' => php_uname('r'),
            'machine' => php_uname('m'),
        ];

        // Attempt to get outbound IP
        try {
            $outboundIp = @file_get_contents('https://api.ipify.org', false, stream_context_create([
                'http' => ['timeout' => 5]
            ]));
            $systemInfo['outbound_ip'] = $outboundIp ?: 'Unable to detect';
        } catch (\Exception $e) {
            $systemInfo['outbound_ip'] = 'Timeout / Error';
        }

        // Diagnostics Data
        $diagnostics = [];

        // 3. CPU Info
        $cpuInfo = @shell_exec("lscpu");
        $diagnostics['cpu'] = $cpuInfo ?: (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN' ? "Not supported on Windows" : "Permission denied / Not supported");

        // 4. RAM Info
        $ram = @shell_exec("free -m");
        $diagnostics['ram'] = $ram ?: (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN' ? "Not supported on Windows" : "Permission denied / Not supported");

        // 5. PHP Extensions
        $diagnostics['extensions'] = get_loaded_extensions();

        // 6. PHP Limits
        $diagnostics['limits'] = [
            'memory_limit' => ini_get('memory_limit'),
            'upload_max_filesize' => ini_get('upload_max_filesize'),
            'post_max_size' => ini_get('post_max_size'),
            'max_execution_time' => ini_get('max_execution_time') . ' sec'
        ];

        // 7. Disk Usage
        $diagnostics['disk'] = [
            'total' => $this->formatSize(disk_total_space("/")),
            'free' => $this->formatSize(disk_free_space("/"))
        ];

        // 8. MySQL Port
        $conn = @fsockopen("127.0.0.1", 3306, $errno, $errstr, 2);
        $diagnostics['mysql_port'] = $conn ? 'Open' : 'Blocked / Offline';
        if ($conn) fclose($conn);

        // 9. cURL Test
        $diagnostics['curl_status'] = function_exists('curl_version') ? 'Working' : 'Not Installed';

        // 10. SMTP Connectivity
        $smtp = ["Gmail" => ["smtp.gmail.com", 587], "Outlook" => ["smtp.office365.com", 587], "Yahoo" => ["smtp.mail.yahoo.com", 587]];
        $diagnostics['smtp'] = [];
        foreach ($smtp as $name => $data) {
            $c = @fsockopen($data[0], $data[1], $e1, $e2, 3);
            $diagnostics['smtp'][$name] = $c ? 'Reachable' : 'Blocked';
            if ($c) fclose($c);
        }

        // 11. DNS Lookup
        $domains = ["google.com", "cloudflare.com", "github.com"];
        $diagnostics['dns'] = [];
        foreach ($domains as $d) {
            $diagnostics['dns'][$d] = gethostbyname($d);
        }

        // 12. File Permissions (Current Public Path)
        $diagnostics['permissions'] = [];
        $files = array_slice(scandir(public_path()), 0, 10); // Limit to 10
        foreach ($files as $f) {
            if ($f != "." && $f != "..") {
                $diagnostics['permissions'][$f] = substr(sprintf('%o', @fileperms(public_path($f))), -4);
            }
        }

        // 13. Recently Modified (Public area)
        $modFiles = [];
        foreach (scandir(base_path()) as $f) {
            if (is_file(base_path($f))) $modFiles[$f] = filemtime(base_path($f));
        }
        arsort($modFiles);
        $diagnostics['recent_files'] = array_slice($modFiles, 0, 10, true);

        // 14. Error Log
        $log = ini_get('error_log');
        $diagnostics['error_log'] = "No error log found";
        if ($log && file_exists($log)) {
            $lines = file($log);
            $diagnostics['error_log'] = implode("", array_slice($lines, -15));
        } elseif (file_exists(storage_path('logs/laravel.log'))) {
            $lines = file(storage_path('logs/laravel.log'));
            $diagnostics['error_log'] = implode("", array_slice($lines, -15));
        }

        // 15. OPcache
        $diagnostics['opcache'] = "OPcache not enabled";
        if (function_exists('opcache_get_status')) {
            $op = opcache_get_status(false);
            $diagnostics['opcache'] = $op ?: "OPcache not enabled";
        }

        // 17. Composer Packages
        $composer = @shell_exec("composer show 2>&1");
        $diagnostics['composer'] = $composer ?: "composer not available / permitted";

        // 18. Active Processes
        $ps = @shell_exec("ps aux 2>&1");
        $diagnostics['processes'] = $ps ?: (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN' ? "Use tasklist on Windows" : "ps aux not permitted");

        // 19. Request Headers & Safe ENV
        $diagnostics['headers'] = getallheaders();
        $safeENV = ["PATH", "USER", "HOME", "TMP", "TEMP"];
        $diagnostics['env'] = [];
        foreach ($_SERVER as $k => $v) {
            if (in_array($k, $safeENV)) $diagnostics['env'][$k] = $v;
        }

        return view('admin.dashboard', compact('systemInfo', 'diagnostics'));
    }

    private function formatSize($bytes)
    {
        $u = ['B', 'KB', 'MB', 'GB', 'TB'];
        $i = 0;
        while ($bytes > 1024 && $i < 4) {
            $bytes /= 1024;
            $i++;
        }
        return round($bytes, 2) . " " . $u[$i];
    }

    public function settings()
    {
        return view('admin.settings');
    }

    public function stripeInfo()
    {
        $webhookUrl = url('/api/payment/stripe/webhook');
        $events = [
            'checkout.session.completed' => 'Occurs when a Checkout Session has been successfully completed.',
            'checkout.session.async_payment_succeeded' => 'Sent when an asynchronous payment (like bank transfer) succeeds.',
            'payment_intent.succeeded' => 'Fires when a payment attempt is successfully confirmed.',
            'payment_intent.payment_failed' => 'Fires when a payment attempt fails or is canceled.',
            'customer.subscription.created' => 'Occurs when a new subscription is started for a customer.',
            'customer.subscription.updated' => 'Sent when a subscription is changed (e.g., upgraded/downgraded).',
            'customer.subscription.deleted' => 'Sent when a subscription is canceled or expires.',
            'invoice.payment_succeeded' => 'Fires when an invoice (including renewals) is successfully paid.',
        ];

        return view('admin.stripe.webhook', compact('webhookUrl', 'events'));
    }

}
