<?php

use Illuminate\Support\Facades\Route;

// Load SystemSettingsRoutes
if (file_exists($SystemSettingsRoutes = __DIR__.'/Common/SystemSettingsRoutes.php')) {
    require $SystemSettingsRoutes;
}

// Load AllowedOriginRoutes
if (file_exists($AllowedOriginRoutes = __DIR__.'/Common/AllowedOriginRoutes.php')) {
    require $AllowedOriginRoutes;
}

// Load UserAuthRoutes
if (file_exists($UserAuthRoutes = __DIR__.'/Users/Auth/UserAuthRoutes.php')) {
    require $UserAuthRoutes;
}

// Load AdminAuthRoutes
if (file_exists($AdminAuthRoutes = __DIR__.'/Admins/Auth/AdminAuthRoutes.php')) {
    require $AdminAuthRoutes;
}

// Load AdminPlanRoutes
if (file_exists($AdminPlanRoutes = __DIR__.'/Admins/Plans/PlanRoutes.php')) {
    require $AdminPlanRoutes;
}


// Load AdminFeatureRoutes
if (file_exists($AdminFeatureRoutes = __DIR__.'/Admins/Plans/FeatureRoutes.php')) {
    require $AdminFeatureRoutes;
}

// Load AdminCouponRoutes
if (file_exists($AdminCouponRoutes = __DIR__.'/Admins/Coupon/CouponRoutes.php')) {
    require $AdminCouponRoutes;
}


// Load NotificationRoutes
if (file_exists($NotificationRoutes = __DIR__.'/Common/NotificationRoutes.php')) {
    require $NotificationRoutes;
}


// Load AdminSupportTickets
if (file_exists($AdminSupportTickets = __DIR__.'/Common/SupportAndConnect/Ticket/Admin/AdminTicketRoutes.php')) {
    require $AdminSupportTickets;
}


// Load UserSupportTickets
if (file_exists($UserSupportTickets = __DIR__.'/Common/SupportAndConnect/Ticket/User/UserTicketRoutes.php')) {
    require $UserSupportTickets;
}


// Load ContactRoutes
if (file_exists($ContactRoutes = __DIR__.'/Common/SupportAndConnect/Contact/ContactRoutes.php')) {
    require $ContactRoutes;
}


// Load MediaRoutes
if (file_exists($MediaRoutes = __DIR__.'/Common/Media/MediaRoutes.php')) {
    require $MediaRoutes;
}


// Load AdminUserRoutes
if (file_exists($AdminUserRoutes = __DIR__.'/Admins/UserManagement/UserManagementRoutes.php')) {
    require $AdminUserRoutes;
}

// Load TwilioRoutes
if (file_exists($TwilioRoutes = __DIR__.'/Common/Twilio/TwilioRoutes.php')) {
    require $TwilioRoutes;
}


// Load UserManagementRoutes
if (file_exists($UserManagementRoutes = __DIR__.'/Users/UserManagement/UserManagementRoutes.php')) {
    require $UserManagementRoutes;
}


// Load PlanPurchaseRoutes
if (file_exists($PlanPurchaseRoutes = __DIR__.'/Users/Subscriptions/PlanPurchaseRoutes.php')) {
    require $PlanPurchaseRoutes;
}


// Load UserPaymentsRoutes
if (file_exists($UserPaymentsRoutes = __DIR__.'/Users/Transaction/UserPaymentsRoutes.php')) {
    require $UserPaymentsRoutes;
}


// Load AdminSubscriptionsRoutes
if (file_exists($AdminSubscriptionsRoutes = __DIR__.'/Admins/Subscriptions/AdminSubscriptionsRoutes.php')) {
    require $AdminSubscriptionsRoutes;
}



// Load BlogCategoryRoutes
if (file_exists($BlogCategoryRoutes = __DIR__.'/Common/Blogs/BlogCategoryRoutes.php')) {
    require $BlogCategoryRoutes;
}


// Load BlogPostRoutes
if (file_exists($BlogPostRoutes = __DIR__.'/Common/Blogs/BlogPostRoutes.php')) {
    require $BlogPostRoutes;
}

// Load EmailTemplateApiRoutes
if (file_exists($EmailTemplateApiRoutes = __DIR__.'/Admins/EmailTemplates/EmailTemplateApiRoutes.php')) {
    require $EmailTemplateApiRoutes;
}

// Load NoticeRoutes
if (file_exists($NoticeRoutes = __DIR__.'/Common/NoticeRoutes.php')) {
    require $NoticeRoutes;
}

// Load UmrahCabRoutes
if (file_exists($UmrahCabRoutes = __DIR__.'/Common/UmrahCabRoutes.php')) {
    require $UmrahCabRoutes;
}

