<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Companies
        Schema::create('uc_companies', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('phone')->nullable();
            $table->string('email')->nullable();
            $table->string('website')->nullable();
            $table->text('address')->nullable();
            $table->boolean('invoice')->default(true);
            $table->boolean('vouchers')->default(true);
            $table->boolean('reminders')->default(true);
            $table->timestamps();
        });

        // 2. Customers
        Schema::create('uc_customers', function (Blueprint $table) {
            $table->id();
            $table->string('custom_id');
            $table->string('name');
            $table->string('company');
            $table->string('contact')->nullable();
            $table->string('registered_by')->nullable();
            $table->string('last_update')->nullable();
            $table->timestamps();
        });

        // 3. Bookings
        Schema::create('uc_bookings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('customer_id')->nullable()->constrained('uc_customers')->onDelete('cascade');
            $table->string('booking_code')->unique();
            $table->string('pickup');
            $table->string('destination');
            $table->date('date');
            $table->time('time');
            $table->string('passengers');
            $table->string('car_type');
            $table->decimal('car_price', 10, 2);
            $table->string('full_name');
            $table->string('email')->nullable();
            $table->string('whatsapp');
            $table->string('flight_no')->nullable();
            $table->text('notes')->nullable();
            $table->string('status')->default('Pending Check');
            $table->timestamps();
        });

        // 4. Services (Auxiliary)
        Schema::create('uc_services', function (Blueprint $table) {
            $table->id();
            $table->foreignId('customer_id')->nullable()->constrained('uc_customers')->onDelete('cascade');
            $table->string('custom_id');
            $table->string('name');
            $table->string('type');
            $table->text('description')->nullable();
            $table->decimal('base_price', 10, 2);
            $table->string('status')->default('Active');
            $table->string('pickup')->nullable();
            $table->decimal('driver_cash', 10, 2)->nullable();
            $table->date('date')->nullable();
            $table->time('time')->nullable();
            $table->timestamps();
        });

        // 5. Flights
        Schema::create('uc_flights', function (Blueprint $table) {
            $table->id();
            $table->foreignId('customer_id')->nullable()->constrained('uc_customers')->onDelete('cascade');
            $table->string('custom_id');
            $table->string('flight_no');
            $table->string('leg');
            $table->date('date');
            $table->time('time');
            $table->string('route');
            $table->string('status')->default('On Time');
            $table->timestamps();
        });

        // 6. Trains
        Schema::create('uc_trains', function (Blueprint $table) {
            $table->id();
            $table->foreignId('customer_id')->nullable()->constrained('uc_customers')->onDelete('cascade');
            $table->string('custom_id');
            $table->string('train_no');
            $table->string('leg');
            $table->date('date');
            $table->time('time');
            $table->string('route');
            $table->string('status')->default('Scheduled');
            $table->timestamps();
        });

        // 7. Followups
        Schema::create('uc_followups', function (Blueprint $table) {
            $table->id();
            $table->foreignId('customer_id')->nullable()->constrained('uc_customers')->onDelete('cascade');
            $table->string('custom_id');
            $table->string('title');
            $table->string('agent');
            $table->string('contact');
            $table->date('date');
            $table->string('status')->default('Pending');
            $table->text('notes')->nullable();
            $table->timestamps();
        });

        // 8. Invoices
        Schema::create('uc_invoices', function (Blueprint $table) {
            $table->id();
            $table->foreignId('customer_id')->nullable()->constrained('uc_customers')->onDelete('cascade');
            $table->string('invoice_code')->unique();
            $table->string('customer');
            $table->date('date');
            $table->decimal('amount', 10, 2);
            $table->decimal('balance', 10, 2);
            $table->string('status')->default('Unpaid');
            $table->timestamps();
        });

        // 9. Ledgers
        Schema::create('uc_ledgers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('customer_id')->nullable()->constrained('uc_customers')->onDelete('cascade');
            $table->string('custom_id');
            $table->string('company');
            $table->date('date');
            $table->string('description');
            $table->decimal('debit', 10, 2)->default(0);
            $table->decimal('credit', 10, 2)->default(0);
            $table->decimal('balance', 10, 2);
            $table->timestamps();
        });

        // 10. Payments
        Schema::create('uc_payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('customer_id')->nullable()->constrained('uc_customers')->onDelete('cascade');
            $table->string('custom_id');
            $table->string('company');
            $table->date('date');
            $table->string('method');
            $table->decimal('amount', 10, 2);
            $table->string('currency')->default('SAR');
            $table->string('status')->default('Pending');
            $table->timestamps();
        });

        // 11. Notices
        Schema::create('uc_notices', function (Blueprint $table) {
            $table->id();
            $table->string('custom_id');
            $table->string('title');
            $table->date('date');
            $table->string('priority');
            $table->string('target');
            $table->text('content');
            $table->timestamps();
        });

        // 12. Fleet
        Schema::create('uc_fleet', function (Blueprint $table) {
            $table->id();
            $table->string('model');
            $table->integer('count');
            $table->integer('active');
            $table->timestamps();
        });

        // 13. Audits
        Schema::create('uc_audits', function (Blueprint $table) {
            $table->id();
            $table->foreignId('customer_id')->nullable()->constrained('uc_customers')->onDelete('cascade');
            $table->string('custom_id');
            $table->string('user_session');
            $table->string('ip_location');
            $table->text('performed_action');
            $table->timestamps();
        });

        // 14. Price Lists Matrix
        Schema::create('uc_price_lists', function (Blueprint $table) {
            $table->id();
            $table->string('route');
            $table->decimal('sedan_price', 10, 2)->default(0);
            $table->string('sedan_dates')->nullable();
            $table->decimal('suv_price', 10, 2)->default(0);
            $table->string('suv_dates')->nullable();
            $table->decimal('van_price', 10, 2)->default(0);
            $table->string('van_dates')->nullable();
            $table->decimal('coach_price', 10, 2)->default(0);
            $table->string('coach_dates')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('uc_price_lists');
        Schema::dropIfExists('uc_audits');
        Schema::dropIfExists('uc_fleet');
        Schema::dropIfExists('uc_notices');
        Schema::dropIfExists('uc_payments');
        Schema::dropIfExists('uc_ledgers');
        Schema::dropIfExists('uc_invoices');
        Schema::dropIfExists('uc_followups');
        Schema::dropIfExists('uc_trains');
        Schema::dropIfExists('uc_flights');
        Schema::dropIfExists('uc_services');
        Schema::dropIfExists('uc_bookings');
        Schema::dropIfExists('uc_customers');
        Schema::dropIfExists('uc_companies');
    }
};
