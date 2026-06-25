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

    // 16. User Management
    require __DIR__ . '/UmrahCab/UserRoutes.php';

    // 17. Performance Reports
    require __DIR__ . '/UmrahCab/PerformanceRoutes.php';

    // 18. B2B Company Panel Routes
    require __DIR__ . '/UmrahCab/CompanyPanelRoutes.php';

    // 19. Admin Chat Support Routes
    require __DIR__ . '/UmrahCab/ChatRoutes.php';

    // 20. Hotels
    require __DIR__ . '/UmrahCab/HotelRoutes.php';

    // 21. Driver Portal Routes
    require __DIR__ . '/UmrahCab/DriverRoutes.php';

    // 22. Driver Management Routes
    require __DIR__ . '/UmrahCab/DriverManageRoutes.php';

    // 23. Sub-Admin Management Routes
    require __DIR__ . '/UmrahCab/SubAdminRoutes.php';

    // 24. Website Global Settings Routes
    require __DIR__ . '/UmrahCab/WebsiteSettingRoutes.php';
});
