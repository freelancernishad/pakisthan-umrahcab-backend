<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // 1. Add columns to the table
        Schema::table('uc_customers', function (Blueprint $table) {
            $table->string('phone')->nullable()->after('company');
            $table->string('secondary_phone')->nullable()->after('phone');
            $table->string('alternative_phone')->nullable()->after('secondary_phone');
            $table->string('email')->nullable()->after('alternative_phone');
            $table->string('passport_no')->nullable()->after('email');
            $table->text('hotel_info')->nullable()->after('passport_no');
            $table->text('notes')->nullable()->after('hotel_info');
        });

        // 2. Parse and migrate existing records
        $customers = DB::table('uc_customers')->get();
        foreach ($customers as $customer) {
            if (empty($customer->contact)) {
                continue;
            }

            $contactStr = $customer->contact;
            $phone = null;
            $secondaryPhone = null;
            $alternativePhone = null;
            $email = null;
            $passportNo = null;
            $hotelInfo = null;
            $notes = null;

            // Extract notes
            if (str_contains($contactStr, ' | Notes: ')) {
                $parts = explode(' | Notes: ', $contactStr);
                $notes = $parts[1] ?? null;
                $contactStr = $parts[0];
            }

            // Extract hotel info
            if (str_contains($contactStr, ' | Hotel: ')) {
                $parts = explode(' | Hotel: ', $contactStr);
                $hotelInfo = $parts[1] ?? null;
                $contactStr = $parts[0];
            }

            // Extract passport
            if (str_contains($contactStr, ' | Passport: ')) {
                $parts = explode(' | Passport: ', $contactStr);
                $passportNo = $parts[1] ?? null;
                $contactStr = $parts[0];
            }

            // Extract email and phones
            if (str_contains($contactStr, ' | Email: ')) {
                $parts = explode(' | Email: ', $contactStr);
                $email = $parts[1] ?? null;
                $phonePart = $parts[0];
            } elseif (str_contains($contactStr, ' (P), ')) {
                // Support legacy format: "Phone1 / Phone2 (P), email@domain (Email)"
                $parts = explode(' (P), ', $contactStr);
                $phonePart = $parts[0] ?? null;
                if (isset($parts[1])) {
                    $email = str_replace(' (Email)', '', $parts[1]);
                }
            } else {
                $phonePart = $contactStr;
            }

            // Clean up email
            if ($email) {
                $email = trim(explode(' | ', $email)[0]);
            }

            // Clean up passport info (if there are trailing fields)
            if ($passportNo) {
                $passportNo = trim(explode(' | ', $passportNo)[0]);
            }

            // Clean up hotel info (if there are trailing fields)
            if ($hotelInfo) {
                $hotelInfo = trim(explode(' | ', $hotelInfo)[0]);
            }

            // Parse phones
            if ($phonePart) {
                $phones = explode(' / ', $phonePart);
                $phone = isset($phones[0]) ? trim($phones[0]) : null;
                $secondaryPhone = isset($phones[1]) ? trim($phones[1]) : null;
                $alternativePhone = isset($phones[2]) ? trim($phones[2]) : null;
            }

            // Update record
            DB::table('uc_customers')
                ->where('id', $customer->id)
                ->update([
                    'phone' => $phone,
                    'secondary_phone' => $secondaryPhone,
                    'alternative_phone' => $alternativePhone,
                    'email' => $email,
                    'passport_no' => $passportNo,
                    'hotel_info' => $hotelInfo,
                    'notes' => $notes,
                ]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('uc_customers', function (Blueprint $table) {
            $table->dropColumn([
                'phone',
                'secondary_phone',
                'alternative_phone',
                'email',
                'passport_no',
                'hotel_info',
                'notes'
            ]);
        });
    }
};
