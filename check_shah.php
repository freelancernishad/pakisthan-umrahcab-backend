<?php

require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\UmrahCab\UcPayment;
use App\Models\UmrahCab\UcLedger;

echo "=== ALL PAYMENTS ===\n";
foreach (UcPayment::orderBy('id', 'desc')->take(10)->get() as $p) {
    echo "ID: {$p->id} | CustomID: '{$p->custom_id}' | Company: '{$p->company}' | Method: {$p->method} | Amount: {$p->amount} | Status: {$p->status} | Ref: '{$p->transaction_ref}'\n";
}
