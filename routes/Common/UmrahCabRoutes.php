<?php

use Illuminate\Support\Facades\Route;
use App\Http\Middleware\AttachJwtFromCookie;
use App\Http\Middleware\AuthenticateAdmin;

Route::prefix('umrahcab')->group(function () {
    // ---------------------------------------------------------
    // 1. Public & Mixed Access Routes (Self-governed or Guest)
    // ---------------------------------------------------------
    // Auth routes
    require __DIR__ . '/UmrahCab/CompanyAuthRoutes.php';
    require __DIR__ . '/UmrahCab/DriverAuthRoutes.php';

    // B2B & Driver Portal
    require __DIR__ . '/UmrahCab/CompanyPanelRoutes.php';
    require __DIR__ . '/UmrahCab/DriverRoutes.php';

    // Mixed access modules (split internally into public/admin)
    require __DIR__ . '/UmrahCab/BookingRoutes.php';
    require __DIR__ . '/UmrahCab/ServiceRoutes.php';
    require __DIR__ . '/UmrahCab/FlightRoutes.php';
    require __DIR__ . '/UmrahCab/TrainRoutes.php';
    require __DIR__ . '/UmrahCab/HotelRoutes.php';
    require __DIR__ . '/UmrahCab/FleetRoutes.php';
    require __DIR__ . '/UmrahCab/NoticeRoutes.php';
    require __DIR__ . '/UmrahCab/PriceListRoutes.php';

    // ---------------------------------------------------------
    // 2. Strictly Administrative Control Routes (Admin Only)
    // ---------------------------------------------------------
    Route::middleware([AttachJwtFromCookie::class, AuthenticateAdmin::class])->group(function () {
        // Customers & Companies
        require __DIR__ . '/UmrahCab/CustomerRoutes.php';
        require __DIR__ . '/UmrahCab/CompanyRoutes.php';

        // Financials & Ledgers
        require __DIR__ . '/UmrahCab/InvoiceRoutes.php';
        require __DIR__ . '/UmrahCab/LedgerRoutes.php';
        require __DIR__ . '/UmrahCab/PaymentRoutes.php';
        require __DIR__ . '/UmrahCab/BalanceRoutes.php';

        // Audits, Followups, Performance
        require __DIR__ . '/UmrahCab/AuditRoutes.php';
        require __DIR__ . '/UmrahCab/FollowupRoutes.php';
        require __DIR__ . '/UmrahCab/PerformanceRoutes.php';

        // Settings, Notices, Price Matrix, Users
        require __DIR__ . '/UmrahCab/UserRoutes.php';
        require __DIR__ . '/UmrahCab/SubAdminRoutes.php';
        require __DIR__ . '/UmrahCab/DriverManageRoutes.php';
        require __DIR__ . '/UmrahCab/ChatRoutes.php';
        require __DIR__ . '/UmrahCab/WebsiteSettingRoutes.php';
    });
});
