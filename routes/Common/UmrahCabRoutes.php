<?php

use Illuminate\Support\Facades\Route;

Route::prefix('umrahcab')->group(function () {
    // 1. Bookings
    require __DIR__ . '/UmrahCab/BookingRoutes.php';

    // 2. Customers
    require __DIR__ . '/UmrahCab/CustomerRoutes.php';

    // 3. Companies
    require __DIR__ . '/UmrahCab/CompanyRoutes.php';

    // 4. Services
    require __DIR__ . '/UmrahCab/ServiceRoutes.php';

    // 5. Flights
    require __DIR__ . '/UmrahCab/FlightRoutes.php';

    // 6. Trains
    require __DIR__ . '/UmrahCab/TrainRoutes.php';

    // 7. Invoices
    require __DIR__ . '/UmrahCab/InvoiceRoutes.php';

    // 8. Ledgers
    require __DIR__ . '/UmrahCab/LedgerRoutes.php';

    // 9. Payments
    require __DIR__ . '/UmrahCab/PaymentRoutes.php';

    // 10. Notices
    require __DIR__ . '/UmrahCab/NoticeRoutes.php';

    // 11. Fleet
    require __DIR__ . '/UmrahCab/FleetRoutes.php';

    // 12. Audits
    require __DIR__ . '/UmrahCab/AuditRoutes.php';

    // 13. Followups
    require __DIR__ . '/UmrahCab/FollowupRoutes.php';

    // 14. Price List Matrix
    require __DIR__ . '/UmrahCab/PriceListRoutes.php';

    // 15. Balance Summary
    require __DIR__ . '/UmrahCab/BalanceRoutes.php';
});
