<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\UmrahCab\UcCompany;
use App\Models\UmrahCab\UcCustomer;
use App\Models\UmrahCab\UcBooking;
use App\Models\UmrahCab\UcService;
use App\Models\UmrahCab\UcFlight;
use App\Models\UmrahCab\UcTrain;
use App\Models\UmrahCab\UcFollowup;
use App\Models\UmrahCab\UcInvoice;
use App\Models\UmrahCab\UcLedger;
use App\Models\UmrahCab\UcPayment;
use App\Models\UmrahCab\UcNotice;
use App\Models\UmrahCab\UcFleet;
use App\Models\UmrahCab\UcAudit;
use App\Models\UmrahCab\UcPriceList;

class UmrahCabSeeder extends Seeder
{
    public function run(): void
    {
        // Truncate tables to allow safe repeated seeding
        \Illuminate\Support\Facades\Schema::disableForeignKeyConstraints();
        UcCompany::truncate();
        UcCustomer::truncate();
        UcBooking::truncate();
        UcService::truncate();
        UcFlight::truncate();
        UcTrain::truncate();
        UcFollowup::truncate();
        UcInvoice::truncate();
        UcLedger::truncate();
        UcPayment::truncate();
        UcNotice::truncate();
        UcFleet::truncate();
        UcAudit::truncate();
        UcPriceList::truncate();
        \Illuminate\Support\Facades\Schema::enableForeignKeyConstraints();

        // 1. Companies
        UcCompany::create([
            'name' => 'Zahid Travels',
            'phone' => '+966 50 123 4567',
            'email' => 'info@zahidtravels.com',
            'website' => 'www.zahidtravels.com',
            'address' => 'Makkah Main St.',
            'invoice' => true,
            'vouchers' => true,
            'reminders' => true
        ]);

        UcCompany::create([
            'name' => 'Al-Latif Group',
            'phone' => '+966 54 987 6543',
            'email' => 'contact@allatif.com',
            'website' => 'www.allatif.com',
            'address' => 'Jeddah Trade Center',
            'invoice' => true,
            'vouchers' => false,
            'reminders' => true
        ]);

        // 2. Customers
        $c1 = UcCustomer::create([
            'custom_id' => '#CST-1',
            'name' => 'Abu Bakar',
            'company' => 'Zahid Travels',
            'contact' => '123456789 (P), N/A (Email)',
            'registered_by' => 'umrahcab (22-May-2026)',
            'last_update' => 'umrahcab (22-May-2026)'
        ]);

        $c2 = UcCustomer::create([
            'custom_id' => '#CST-2',
            'name' => 'Amjad',
            'company' => 'Zahid Travels',
            'contact' => '123456 (P), N/A (Email)',
            'registered_by' => 'umrahcab (22-May-2026)',
            'last_update' => 'No edits'
        ]);

        $c3 = UcCustomer::create([
            'custom_id' => '#CST-3',
            'name' => 'Mohammed Siddique',
            'company' => 'Al-Latif Group',
            'contact' => '987654321 (P), sid@allatif.com',
            'registered_by' => 'umrahcab (23-May-2026)',
            'last_update' => 'No edits'
        ]);

        // 3. Bookings
        UcBooking::create([
            'customer_id' => $c2->id,
            'booking_code' => 'UCB-8736',
            'pickup' => 'Jeddah Airport',
            'destination' => 'Makkah Hotel',
            'date' => '2026-05-25',
            'time' => '10:30:00',
            'passengers' => '1-4',
            'car_type' => 'Sedan',
            'car_price' => 300,
            'full_name' => 'Zubair Ahmad',
            'email' => 'zubair@example.com',
            'whatsapp' => '+966567799616',
            'flight_no' => 'SV-812',
            'notes' => 'Awaiting flight confirmation from client.',
            'status' => 'Active Dispatch'
        ]);

        UcBooking::create([
            'customer_id' => $c1->id,
            'booking_code' => 'UCB-1092',
            'pickup' => 'Madinah Hotel',
            'destination' => 'Jeddah Airport',
            'date' => '2026-05-28',
            'time' => '14:00:00',
            'passengers' => '5-7',
            'car_type' => 'GMC Yukon XL',
            'car_price' => 550,
            'full_name' => 'Abu Bakar',
            'email' => 'bakar@example.com',
            'whatsapp' => '+966567799616',
            'flight_no' => 'SV-442',
            'notes' => 'Requires extra large boot space for 6 bags.',
            'status' => 'Confirmed Booking'
        ]);

        UcBooking::create([
            'customer_id' => $c1->id,
            'booking_code' => 'UCB-1093',
            'pickup' => 'Jeddah Airport',
            'destination' => 'Madinah Hotel',
            'date' => '2026-05-29',
            'time' => '09:00:00',
            'passengers' => '1-4',
            'car_type' => 'Sedan',
            'car_price' => 350,
            'full_name' => 'Abu Bakar',
            'email' => 'bakar@example.com',
            'whatsapp' => '+966567799616',
            'flight_no' => 'SV-442',
            'notes' => 'Relational test multi-status active dispatch booking.',
            'status' => 'Active Dispatch'
        ]);

        UcBooking::create([
            'customer_id' => $c1->id,
            'booking_code' => 'UCB-1094',
            'pickup' => 'Makkah Hotel',
            'destination' => 'Jeddah Airport',
            'date' => '2026-05-30',
            'time' => '18:00:00',
            'passengers' => '5-7',
            'car_type' => 'GMC Yukon XL',
            'car_price' => 600,
            'full_name' => 'Abu Bakar',
            'email' => 'bakar@example.com',
            'whatsapp' => '+966567799616',
            'flight_no' => 'EK-201',
            'notes' => 'Relational test multi-status completed booking.',
            'status' => 'Completed'
        ]);

        UcBooking::create([
            'customer_id' => $c1->id,
            'booking_code' => 'UCB-1095',
            'pickup' => 'Makkah Hotel',
            'destination' => 'Taif Ziyarah',
            'date' => '2026-05-31',
            'time' => '11:00:00',
            'passengers' => '1-4',
            'car_type' => 'Sedan',
            'car_price' => 200,
            'full_name' => 'Abu Bakar',
            'email' => 'bakar@example.com',
            'whatsapp' => '+966567799616',
            'flight_no' => 'N/A',
            'notes' => 'Relational test multi-status cancelled booking.',
            'status' => 'Cancelled'
        ]);

        // 4. Services
        UcService::create([
            'customer_id' => $c3->id,
            'custom_id' => '#SRV-1',
            'name' => 'Premium Umrah Visa Service',
            'type' => 'Visa',
            'description' => 'Electronic Umrah visa compilation and processing in 24 hours.',
            'base_price' => 450.00,
            'status' => 'Active'
        ]);

        UcService::create([
            'customer_id' => $c1->id,
            'custom_id' => '#SRV-2',
            'name' => 'Private Makkah Ziyarah Tour',
            'type' => 'Ziyarah',
            'description' => 'Guided half-day historical tour to Jabal al-Nour, Arafat, and Mina.',
            'base_price' => 250.00,
            'status' => 'Active'
        ]);

        UcService::create([
            'customer_id' => $c1->id,
            'custom_id' => '#SRV-3',
            'name' => 'VIP VIP Lounge Access',
            'type' => 'Visa',
            'description' => 'Airport lounge access with unlimited refreshments.',
            'base_price' => 150.00,
            'status' => 'Completed'
        ]);

        UcService::create([
            'customer_id' => $c1->id,
            'custom_id' => '#SRV-4',
            'name' => 'SIM Card Procurement',
            'type' => 'Catalogue',
            'description' => 'Local 5G SIM card with 20GB pre-loaded data package.',
            'base_price' => 40.00,
            'status' => 'Pending'
        ]);

        // Service Catalogue Directory Items
        UcService::create(['customer_id' => null, 'custom_id' => '#SCI-1', 'name' => '(Juice, Cake & Lays)', 'type' => 'Catalogue', 'description' => 'Entry By: Fayyazali | Entry Date: 12 Nov, 2025 | Edited By: N/A | Edited Date: N/A', 'base_price' => 0.00, 'status' => 'Active']);
        UcService::create(['customer_id' => null, 'custom_id' => '#SCI-2', 'name' => '4 kg Ajwa Dates 2 boxes', 'type' => 'Catalogue', 'description' => 'Entry By: haseeb | Entry Date: 08 Oct, 2025 | Edited By: N/A | Edited Date: N/A', 'base_price' => 0.00, 'status' => 'Active']);
        UcService::create(['customer_id' => null, 'custom_id' => '#SCI-3', 'name' => 'Additional Staff', 'type' => 'Catalogue', 'description' => 'Entry By: arehman | Entry Date: 27 Jun, 2024 | Edited By: N/A | Edited Date: N/A', 'base_price' => 0.00, 'status' => 'Active']);
        UcService::create(['customer_id' => null, 'custom_id' => '#SCI-4', 'name' => 'Ahram', 'type' => 'Catalogue', 'description' => 'Entry By: sania | Entry Date: 24 Dec, 2024 | Edited By: N/A | Edited Date: N/A', 'base_price' => 0.00, 'status' => 'Active']);
        UcService::create(['customer_id' => null, 'custom_id' => '#SCI-5', 'name' => 'Ajwa Dates', 'type' => 'Catalogue', 'description' => 'Entry By: omer | Entry Date: 02 Nov, 2025 | Edited By: N/A | Edited Date: N/A', 'base_price' => 0.00, 'status' => 'Active']);
        UcService::create(['customer_id' => null, 'custom_id' => '#SCI-6', 'name' => 'Albaik', 'type' => 'Catalogue', 'description' => 'Entry By: hammad | Entry Date: 13 Jul, 2025 | Edited By: N/A | Edited Date: N/A', 'base_price' => 0.00, 'status' => 'Active']);
        UcService::create(['customer_id' => null, 'custom_id' => '#SCI-7', 'name' => 'Baby Chair', 'type' => 'Catalogue', 'description' => 'Entry By: admin | Entry Date: 23 Apr, 2024 | Edited By: N/A | Edited Date: N/A', 'base_price' => 0.00, 'status' => 'Active']);
        UcService::create(['customer_id' => null, 'custom_id' => '#SCI-8', 'name' => 'Breakfast', 'type' => 'Catalogue', 'description' => 'Entry By: muhabiya | Entry Date: 19 Feb, 2025 | Edited By: N/A | Edited Date: N/A', 'base_price' => 0.00, 'status' => 'Active']);
        UcService::create(['customer_id' => null, 'custom_id' => '#SCI-9', 'name' => 'Cack', 'type' => 'Catalogue', 'description' => 'Entry By: muhabiya | Entry Date: 19 Dec, 2024 | Edited By: N/A | Edited Date: N/A', 'base_price' => 0.00, 'status' => 'Active']);
        UcService::create(['customer_id' => null, 'custom_id' => '#SCI-10', 'name' => 'Cacke', 'type' => 'Catalogue', 'description' => 'Entry By: muhabiya | Entry Date: 19 Dec, 2024 | Edited By: N/A | Edited Date: N/A', 'base_price' => 0.00, 'status' => 'Active']);
        UcService::create(['customer_id' => null, 'custom_id' => '#SCI-11', 'name' => 'Cake', 'type' => 'Catalogue', 'description' => 'Entry By: hammad | Entry Date: 06 Aug, 2025 | Edited By: N/A | Edited Date: N/A', 'base_price' => 0.00, 'status' => 'Active']);
        UcService::create(['customer_id' => null, 'custom_id' => '#SCI-12', 'name' => 'Dinner', 'type' => 'Catalogue', 'description' => 'Entry By: muhabiya | Entry Date: 19 Dec, 2024 | Edited By: N/A | Edited Date: N/A', 'base_price' => 0.00, 'status' => 'Active']);
        UcService::create(['customer_id' => null, 'custom_id' => '#SCI-13', 'name' => 'Female Tour Guide', 'type' => 'Catalogue', 'description' => 'Entry By: hammad | Entry Date: 23 Aug, 2025 | Edited By: N/A | Edited Date: N/A', 'base_price' => 0.00, 'status' => 'Active']);
        UcService::create(['customer_id' => null, 'custom_id' => '#SCI-14', 'name' => 'Female Umrah Guide', 'type' => 'Catalogue', 'description' => 'Entry By: hammad | Entry Date: 18 Jul, 2025 | Edited By: hammad | Edited Date: 18 Jul, 2025', 'base_price' => 0.00, 'status' => 'Active']);
        UcService::create(['customer_id' => null, 'custom_id' => '#SCI-15', 'name' => 'Flower Bouquet & Gift Basket', 'type' => 'Catalogue', 'description' => 'Entry By: Mohsin | Entry Date: 25 Aug, 2025 | Edited By: N/A | Edited Date: N/A', 'base_price' => 0.00, 'status' => 'Active']);
        UcService::create(['customer_id' => null, 'custom_id' => '#SCI-16', 'name' => 'Flowers Bouqet', 'type' => 'Catalogue', 'description' => 'Entry By: muhabiya | Entry Date: 18 Dec, 2024 | Edited By: N/A | Edited Date: N/A', 'base_price' => 0.00, 'status' => 'Active']);
        UcService::create(['customer_id' => null, 'custom_id' => '#SCI-17', 'name' => 'Fruit Basket + Juice + Croissant', 'type' => 'Catalogue', 'description' => 'Entry By: haseeb | Entry Date: 04 Oct, 2025 | Edited By: haseeb | Edited Date: 04 Oct, 2025', 'base_price' => 0.00, 'status' => 'Active']);

        // 5. Flights
        UcFlight::create([
            'customer_id' => $c3->id,
            'custom_id' => '#FLT-101',
            'flight_no' => 'SV-321',
            'leg' => 'Arrival',
            'date' => '2026-06-01',
            'time' => '11:30:00',
            'route' => 'JED → MAK',
            'status' => 'On Time'
        ]);

        UcFlight::create([
            'customer_id' => $c2->id,
            'custom_id' => '#FLT-102',
            'flight_no' => 'PK-786',
            'leg' => 'Both Legs',
            'date' => '2026-06-02',
            'time' => '18:45:00',
            'route' => 'LHE → JED → MAK',
            'status' => 'Delayed'
        ]);

        UcFlight::create([
            'customer_id' => $c1->id,
            'custom_id' => '#FLT-103',
            'flight_no' => 'EK-201',
            'leg' => 'Arrival',
            'date' => '2026-06-03',
            'time' => '09:15:00',
            'route' => 'DXB → JED',
            'status' => 'On Time'
        ]);

        UcFlight::create([
            'customer_id' => $c1->id,
            'custom_id' => '#FLT-104',
            'flight_no' => 'SV-442',
            'leg' => 'Arrival',
            'date' => '2026-06-04',
            'time' => '14:30:00',
            'route' => 'RUH → JED',
            'status' => 'Delayed'
        ]);

        UcFlight::create([
            'customer_id' => $c1->id,
            'custom_id' => '#FLT-105',
            'flight_no' => 'PK-786',
            'leg' => 'Both Legs',
            'date' => '2026-06-05',
            'time' => '19:45:00',
            'route' => 'LHE → JED',
            'status' => 'Cancelled'
        ]);

        // 6. Trains
        UcTrain::create([
            'customer_id' => $c3->id,
            'custom_id' => '#TRN-201',
            'train_no' => 'HHR-10',
            'leg' => 'Departure Only',
            'date' => '2026-06-01',
            'time' => '14:00:00',
            'route' => 'MAK → MED',
            'status' => 'Scheduled'
        ]);

        UcTrain::create([
            'customer_id' => $c2->id,
            'custom_id' => '#TRN-202',
            'train_no' => 'HHR-20',
            'leg' => 'Departure Only',
            'date' => '2026-06-02',
            'time' => '16:30:00',
            'route' => 'MED → MAK',
            'status' => 'Scheduled'
        ]);

        UcTrain::create([
            'customer_id' => $c1->id,
            'custom_id' => '#TRN-203',
            'train_no' => 'HHR-30',
            'leg' => 'Departure Only',
            'date' => '2026-06-03',
            'time' => '19:00:00',
            'route' => 'MAK → JED',
            'status' => 'Scheduled'
        ]);

        UcTrain::create([
            'customer_id' => $c1->id,
            'custom_id' => '#TRN-204',
            'train_no' => 'HHR-10',
            'leg' => 'Departure Only',
            'date' => '2026-06-04',
            'time' => '15:30:00',
            'route' => 'JED → MAK',
            'status' => 'Departed'
        ]);

        UcTrain::create([
            'customer_id' => $c1->id,
            'custom_id' => '#TRN-205',
            'train_no' => 'HHR-20',
            'leg' => 'Departure Only',
            'date' => '2026-06-05',
            'time' => '17:00:00',
            'route' => 'MED → MAK',
            'status' => 'Arrived'
        ]);

        // 7. Followups
        UcFollowup::create([
            'customer_id' => $c2->id,
            'custom_id' => '#FLP-501',
            'title' => 'Confirm pickup timing',
            'agent' => 'Zubair Ahmad',
            'contact' => '050123456',
            'date' => '2026-05-25',
            'status' => 'Pending',
            'notes' => 'Awaiting flight confirmation from client.'
        ]);

        // 8. Invoices
        UcInvoice::create([
            'customer_id' => $c1->id,
            'invoice_code' => 'INV-2026-001',
            'customer' => 'Zahid Travels',
            'date' => '2026-05-22',
            'amount' => 4500.00,
            'balance' => 500.00,
            'status' => 'Paid'
        ]);

        UcInvoice::create([
            'customer_id' => $c3->id,
            'invoice_code' => 'INV-2026-002',
            'customer' => 'Al-Latif Group',
            'date' => '2026-05-24',
            'amount' => 7800.00,
            'balance' => 7800.00,
            'status' => 'Unpaid'
        ]);

        // 9. Ledgers
        UcLedger::create([
            'customer_id' => $c1->id,
            'custom_id' => 'LED-8743',
            'company' => 'Zahid Travels',
            'date' => '2026-05-22',
            'description' => 'Deposit Received',
            'debit' => 0.00,
            'credit' => 4000.00,
            'balance' => 4000.00
        ]);

        UcLedger::create([
            'customer_id' => $c1->id,
            'custom_id' => 'LED-8744',
            'company' => 'Zahid Travels',
            'date' => '2026-05-22',
            'description' => 'Booking transport charges UCB-8736',
            'debit' => 300.00,
            'credit' => 0.00,
            'balance' => 3700.00
        ]);

        // 10. Payments
        UcPayment::create([
            'customer_id' => $c1->id,
            'custom_id' => 'PAY-9012',
            'company' => 'Zahid Travels',
            'date' => '2026-05-22',
            'method' => 'Bank Transfer',
            'amount' => 4000.00,
            'currency' => 'SAR',
            'status' => 'Verified'
        ]);

        // 11. Notices
        UcNotice::create([
            'custom_id' => 'NTC-01',
            'title' => 'Hajj Route Rules Updated',
            'date' => '2026-05-24',
            'priority' => 'High',
            'target' => 'Admin',
            'content' => 'Please ensure all drivers have Hajj route clearances before boarding pilgrims.'
        ]);

        // 12. Fleet
        UcFleet::create([
            'model' => 'Sedan (Core)',
            'count' => 25,
            'active' => 20
        ]);
        UcFleet::create([
            'model' => 'Hyundai Staria (Core)',
            'count' => 15,
            'active' => 12
        ]);
        UcFleet::create([
            'model' => 'GMC XL Yukon (Core)',
            'count' => 10,
            'active' => 8
        ]);
        UcFleet::create([
            'model' => 'Coaster (Core)',
            'count' => 5,
            'active' => 4
        ]);

        // 13. Audits
        UcAudit::create([
            'customer_id' => $c1->id,
            'custom_id' => '#AUD-4912',
            'user_session' => 'umrahcab',
            'ip_location' => '192.168.0.104',
            'performed_action' => 'Unlocked Extras utilities panel via passcode'
        ]);
        UcAudit::create([
            'customer_id' => $c2->id,
            'custom_id' => '#AUD-4911',
            'user_session' => 'umrahcab',
            'ip_location' => '192.168.0.104',
            'performed_action' => 'Registered transport booking record UCB-8736'
        ]);
        UcAudit::create([
            'customer_id' => $c3->id,
            'custom_id' => '#AUD-4910',
            'user_session' => 'umrahcab',
            'ip_location' => '192.168.0.104',
            'performed_action' => 'Dashboard login authorization verification success'
        ]);

        // 14. Price Lists
        UcPriceList::create([
            'route' => 'Jeddah Airport To Makkah Hotel',
            'sedan_price' => 300.00,
            'suv_price' => 550.00,
            'van_price' => 500.00,
            'coach_price' => 900.00
        ]);
        UcPriceList::create([
            'route' => 'Makkah Hotel To Jeddah Airport',
            'sedan_price' => 300.00,
            'suv_price' => 550.00,
            'van_price' => 500.00,
            'coach_price' => 900.00
        ]);
        UcPriceList::create([
            'route' => 'Madinah Hotel To Makkah Hotel',
            'sedan_price' => 300.00,
            'suv_price' => 550.00,
            'van_price' => 500.00,
            'coach_price' => 900.00
        ]);
        UcPriceList::create([
            'route' => 'Makkah Hotel To Madinah Hotel',
            'sedan_price' => 300.00,
            'suv_price' => 550.00,
            'van_price' => 500.00,
            'coach_price' => 900.00
        ]);

        // 15. Dynamic relational generation (2000 customers)
        $customersData = [];
        $bookingsData = [];
        $servicesData = [];
        $flightsData = [];
        $trainsData = [];
        $followupsData = [];
        $invoicesData = [];
        $ledgersData = [];
        $paymentsData = [];
        $auditsData = [];

        $companiesList = ['Zahid Travels', 'Al-Latif Group', 'Haramain Express', 'Makkah Tours'];
        $carTypes = ['Sedan', 'GMC Yukon XL', 'Hyundai Staria', 'Coaster'];
        $pickups = ['Jeddah Airport', 'Makkah Hotel', 'Madinah Hotel', 'Yanbu Airport'];
        $destinations = ['Makkah Hotel', 'Madinah Hotel', 'Jeddah Airport', 'Taif Ziyarah'];
        $flightNumbers = ['SV-101', 'PK-786', 'EK-201', 'SV-321', 'QR-102', 'WY-303'];
        $trainNumbers = ['HHR-10', 'HHR-20', 'HHR-30', 'HHR-40', 'HHR-50'];
        $routes = ['JED → MAK', 'LHE → JED → MAK', 'MAK → MED', 'MED → MAK', 'MAK → JED'];

        $now = now()->toDateTimeString();

        for ($i = 4; $i <= 2003; $i++) {
            $comp = $companiesList[array_rand($companiesList)];
            $name = "Customer " . $i;
            
            // Prepare Customer
            $customersData[] = [
                'id' => $i,
                'custom_id' => '#CST-' . $i,
                'name' => $name,
                'company' => $comp,
                'contact' => (966000000 + $i) . ' (P), customer' . $i . '@example.com (Email) | Notes: Dynamic Customer ' . $i,
                'registered_by' => 'umrahcab',
                'last_update' => 'No edits',
                'created_at' => $now,
                'updated_at' => $now
            ];

            // Generate multiple bookings (1 to 3) for this customer
            $numBookings = rand(1, 3);
            for ($b = 1; $b <= $numBookings; $b++) {
                $bookingsData[] = [
                    'customer_id' => $i,
                    'booking_code' => 'UCB-' . (10000 * $b + $i),
                    'pickup' => $pickups[array_rand($pickups)],
                    'destination' => $destinations[array_rand($destinations)],
                    'date' => date('Y-m-d', strtotime("+$i days")),
                    'time' => sprintf('%02d:%02d:00', rand(0, 23), rand(0, 59)),
                    'passengers' => rand(1, 4) . '-' . rand(5, 7),
                    'car_type' => $carTypes[array_rand($carTypes)],
                    'car_price' => rand(250, 950),
                    'full_name' => $name,
                    'email' => 'customer' . $i . '@example.com',
                    'whatsapp' => '+966' . (560000000 + $i),
                    'flight_no' => $flightNumbers[array_rand($flightNumbers)],
                    'notes' => "Auto-generated booking {$b} for customer {$i}",
                    'status' => ['Confirmed Booking', 'Active Dispatch', 'Completed', 'Cancelled'][rand(0, 3)],
                    'created_at' => $now,
                    'updated_at' => $now
                ];
            }

            // Generate multiple services (1 to 3)
            $numServices = rand(1, 3);
            for ($s = 1; $s <= $numServices; $s++) {
                $servicesData[] = [
                    'customer_id' => $i,
                    'custom_id' => '#SRV-' . (10000 * $s + $i),
                    'name' => ['Visa Processing Service', 'VIP Lounge Access', 'SIM Card Procurement', 'Private Tour'][$s - 1] ?? 'Extra Service ' . $s,
                    'type' => ['Visa', 'Ziyarah', 'Catalogue', 'Visa'][rand(0, 3)],
                    'description' => "Relational service transaction {$s} for {$name}",
                    'base_price' => rand(150, 800),
                    'status' => ['Active', 'Completed', 'Pending'][rand(0, 2)],
                    'created_at' => $now,
                    'updated_at' => $now
                ];
            }

            // Generate multiple flights (1 to 3)
            $numFlights = rand(1, 3);
            for ($f = 1; $f <= $numFlights; $f++) {
                $flightsData[] = [
                    'customer_id' => $i,
                    'custom_id' => '#FLT-' . (10000 * $f + $i),
                    'flight_no' => $flightNumbers[array_rand($flightNumbers)],
                    'leg' => ['Arrival', 'Departure', 'Both Legs'][rand(0, 2)],
                    'date' => date('Y-m-d', strtotime("+$i days")),
                    'time' => sprintf('%02d:%02d:00', rand(0, 23), rand(0, 59)),
                    'route' => $routes[array_rand($routes)],
                    'status' => ['On Time', 'Delayed', 'Cancelled'][rand(0, 2)],
                    'created_at' => $now,
                    'updated_at' => $now
                ];
            }

            // Generate multiple trains (1 to 3)
            $numTrains = rand(1, 3);
            for ($t = 1; $t <= $numTrains; $t++) {
                $trainsData[] = [
                    'customer_id' => $i,
                    'custom_id' => '#TRN-' . (10000 * $t + $i),
                    'train_no' => $trainNumbers[array_rand($trainNumbers)],
                    'leg' => ['Arrival', 'Departure Only', 'Both Legs'][rand(0, 2)],
                    'date' => date('Y-m-d', strtotime("+$i days")),
                    'time' => sprintf('%02d:%02d:00', rand(0, 23), rand(0, 59)),
                    'route' => $routes[array_rand($routes)],
                    'status' => ['Scheduled', 'Departed', 'Arrived'][rand(0, 2)],
                    'created_at' => $now,
                    'updated_at' => $now
                ];
            }

            // Generate multiple followups (1 to 3)
            $numFollowups = rand(1, 3);
            for ($fl = 1; $fl <= $numFollowups; $fl++) {
                $followupsData[] = [
                    'customer_id' => $i,
                    'custom_id' => '#FLP-' . (10000 * $fl + $i),
                    'title' => 'Follow up task #' . $fl . ' for ' . $name,
                    'agent' => 'Agent ' . rand(1, 10),
                    'contact' => '+966' . (560000000 + $i),
                    'date' => date('Y-m-d', strtotime("+$i days")),
                    'status' => ['Pending', 'Completed', 'In Progress'][rand(0, 2)],
                    'notes' => "Auto-generated followup {$fl} for customer {$i}",
                    'created_at' => $now,
                    'updated_at' => $now
                ];
            }

            // Generate multiple invoices (1 to 3)
            $numInvoices = rand(1, 3);
            for ($inv = 1; $inv <= $numInvoices; $inv++) {
                $invoiceAmt = rand(1500, 12000);
                $invoiceBal = rand(0, 1) ? 0.00 : rand(100, $invoiceAmt);
                $invoicesData[] = [
                    'customer_id' => $i,
                    'invoice_code' => 'INV-2026-' . sprintf('%04d-%02d', $i, $inv),
                    'customer' => $comp,
                    'date' => date('Y-m-d', strtotime("-$i days")),
                    'amount' => $invoiceAmt,
                    'balance' => $invoiceBal,
                    'status' => $invoiceBal == 0.00 ? 'Paid' : ($invoiceBal == $invoiceAmt ? 'Unpaid' : 'Partially Paid'),
                    'created_at' => $now,
                    'updated_at' => $now
                ];
            }

            // Generate multiple ledgers (1 to 3)
            $numLedgers = rand(1, 3);
            for ($led = 1; $led <= $numLedgers; $led++) {
                $ledgersData[] = [
                    'customer_id' => $i,
                    'custom_id' => 'LED-' . (10000 * $led + $i),
                    'company' => $comp,
                    'date' => date('Y-m-d', strtotime("-$i days")),
                    'description' => "Automatic transaction ledger entry {$led} for customer {$i}",
                    'debit' => rand(0, 1) ? rand(200, 1000) : 0.00,
                    'credit' => rand(0, 1) ? rand(200, 1000) : 0.00,
                    'balance' => rand(1000, 5000),
                    'created_at' => $now,
                    'updated_at' => $now
                ];
            }

            // Generate multiple payments (1 to 3)
            $numPayments = rand(1, 3);
            for ($pay = 1; $pay <= $numPayments; $pay++) {
                $paymentsData[] = [
                    'customer_id' => $i,
                    'custom_id' => 'PAY-' . (10000 * $pay + $i),
                    'company' => $comp,
                    'date' => date('Y-m-d', strtotime("-$i days")),
                    'method' => ['Bank Transfer', 'Stripe', 'Cash', 'Credit Card'][rand(0, 3)],
                    'amount' => rand(500, 8000),
                    'currency' => 'SAR',
                    'status' => ['Verified', 'Pending', 'Failed'][rand(0, 2)],
                    'created_at' => $now,
                    'updated_at' => $now
                ];
            }

            // Generate multiple audits (1 to 3)
            $numAudits = rand(1, 3);
            for ($aud = 1; $aud <= $numAudits; $aud++) {
                $auditsData[] = [
                    'customer_id' => $i,
                    'custom_id' => '#AUD-' . (10000 * $aud + $i),
                    'user_session' => 'agent_' . rand(1, 10),
                    'ip_location' => '192.168.1.' . rand(2, 254),
                    'performed_action' => "Executed relational action {$aud} for customer {$i}",
                    'created_at' => $now,
                    'updated_at' => $now
                ];
            }
        }

        // Chunk insert to optimize performance and memory usage
        foreach (array_chunk($customersData, 500) as $chunk) {
            UcCustomer::insert($chunk);
        }
        foreach (array_chunk($bookingsData, 500) as $chunk) {
            UcBooking::insert($chunk);
        }
        foreach (array_chunk($servicesData, 500) as $chunk) {
            UcService::insert($chunk);
        }
        foreach (array_chunk($flightsData, 500) as $chunk) {
            UcFlight::insert($chunk);
        }
        foreach (array_chunk($trainsData, 500) as $chunk) {
            UcTrain::insert($chunk);
        }
        foreach (array_chunk($followupsData, 500) as $chunk) {
            UcFollowup::insert($chunk);
        }
        foreach (array_chunk($invoicesData, 500) as $chunk) {
            UcInvoice::insert($chunk);
        }
        foreach (array_chunk($ledgersData, 500) as $chunk) {
            UcLedger::insert($chunk);
        }
        foreach (array_chunk($paymentsData, 500) as $chunk) {
            UcPayment::insert($chunk);
        }
        foreach (array_chunk($auditsData, 500) as $chunk) {
            UcAudit::insert($chunk);
        }
    }
}
