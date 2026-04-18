<?php

namespace App\Enums;

enum PlanFeature: string
{
    // ── Module Access ───────────────────────────────
    case ANALYTICS = 'analytics';
    case ADVANCED_REPORTS = 'advanced-reports';
    case API_ACCESS = 'api-access';
    case BULK_IMPORT = 'bulk-import';
    case BULK_EXPORT = 'bulk-export';
    case BARCODE_SCANNING = 'barcode-scanning';
    case LOW_STOCK_ALERTS = 'low-stock-alerts';
    case MULTI_STORE = 'multi-store';
    case AUDIT_LOG = 'audit-log';
    case CUSTOM_ROLES = 'custom-roles';
    case PRIORITY_SUPPORT = 'priority-support';

    // ── Quota/Limit Features ────────────────────────
    case MAX_ITEMS = 'max-items';
    case MAX_STORES = 'max-stores';
    case MAX_TEAM_MEMBERS = 'max-team-members';
    case STORAGE_LIMIT_MB = 'storage-limit-mb';

    /**
     * Human-readable label for the admin UI
     */
    public function label(): string
    {
        return match ($this) {
            self::ANALYTICS => 'Analytics Dashboard',
            self::ADVANCED_REPORTS => 'Advanced Reports',
            self::API_ACCESS => 'API Access',
            self::BULK_IMPORT => 'Bulk Import',
            self::BULK_EXPORT => 'Bulk Export',
            self::BARCODE_SCANNING => 'Barcode Scanning',
            self::LOW_STOCK_ALERTS => 'Low Stock Alerts',
            self::MULTI_STORE => 'Multi-Store Management',
            self::AUDIT_LOG => 'Audit Log',
            self::CUSTOM_ROLES => 'Custom Roles',
            self::PRIORITY_SUPPORT => 'Priority Support',
            self::MAX_ITEMS => 'Maximum Items',
            self::MAX_STORES => 'Maximum Stores',
            self::MAX_TEAM_MEMBERS => 'Maximum Team Members',
            self::STORAGE_LIMIT_MB => 'Storage Limit (MB)',
        };
    }

    /**
     * What kind of feature is this?
     * 'boolean' = on/off toggle
     * 'quota'   = has a numeric value (limit)
     */
    public function type(): string
    {
        return match ($this) {
            self::MAX_ITEMS,
            self::MAX_STORES,
            self::MAX_TEAM_MEMBERS,
            self::STORAGE_LIMIT_MB => 'quota',
            default => 'boolean',
        };
    }

    /**
     * Description for the admin UI
     */
    public function description(): string
    {
        return match ($this) {
            self::ANALYTICS => 'Access to analytics dashboard with charts and insights',
            self::ADVANCED_REPORTS => 'Generate advanced inventory and sales reports',
            self::API_ACCESS => 'Access to REST API for third-party integrations',
            self::BULK_IMPORT => 'Import items in bulk via CSV/Excel',
            self::BULK_EXPORT => 'Export inventory data to CSV/Excel',
            self::BARCODE_SCANNING => 'Scan barcodes for quick stock management',
            self::LOW_STOCK_ALERTS => 'Get notified when items are running low',
            self::MULTI_STORE => 'Manage multiple store locations',
            self::AUDIT_LOG => 'Track all changes with detailed audit trail',
            self::CUSTOM_ROLES => 'Create and assign custom team roles',
            self::PRIORITY_SUPPORT => 'Get priority customer support',
            self::MAX_ITEMS => 'Maximum number of inventory items (-1 = unlimited)',
            self::MAX_STORES => 'Maximum number of stores (-1 = unlimited)',
            self::MAX_TEAM_MEMBERS => 'Maximum team members per store (-1 = unlimited)',
            self::STORAGE_LIMIT_MB => 'Storage limit in megabytes (-1 = unlimited)',
        };
    }

    /**
     * Icon for the admin UI (FontAwesome class)
     */
    public function icon(): string
    {
        return match ($this) {
            self::ANALYTICS => 'fa-chart-pie',
            self::ADVANCED_REPORTS => 'fa-file-alt',
            self::API_ACCESS => 'fa-plug',
            self::BULK_IMPORT => 'fa-file-import',
            self::BULK_EXPORT => 'fa-file-export',
            self::BARCODE_SCANNING => 'fa-barcode',
            self::LOW_STOCK_ALERTS => 'fa-bell',
            self::MULTI_STORE => 'fa-store',
            self::AUDIT_LOG => 'fa-history',
            self::CUSTOM_ROLES => 'fa-user-shield',
            self::PRIORITY_SUPPORT => 'fa-headset',
            self::MAX_ITEMS => 'fa-boxes',
            self::MAX_STORES => 'fa-warehouse',
            self::MAX_TEAM_MEMBERS => 'fa-users',
            self::STORAGE_LIMIT_MB => 'fa-database',
        };
    }

    /**
     * Group features for the admin UI
     */
    public function group(): string
    {
        return match ($this) {
            self::ANALYTICS,
            self::ADVANCED_REPORTS => 'Reporting & Analytics',

            self::BULK_IMPORT,
            self::BULK_EXPORT,
            self::BARCODE_SCANNING => 'Inventory Tools',

            self::API_ACCESS,
            self::AUDIT_LOG,
            self::CUSTOM_ROLES => 'Advanced Features',

            self::LOW_STOCK_ALERTS,
            self::PRIORITY_SUPPORT => 'Notifications & Support',

            self::MAX_ITEMS,
            self::MAX_STORES,
            self::MAX_TEAM_MEMBERS,
            self::STORAGE_LIMIT_MB,
            self::MULTI_STORE => 'Limits & Quotas',
        };
    }

    /**
     * Get all features grouped for the admin UI
     */
    public static function grouped(): array
    {
        $grouped = [];
        foreach (self::cases() as $feature) {
            $grouped[$feature->group()][] = $feature;
        }

        return $grouped;
    }

    /**
     * Get all boolean features
     */
    public static function booleanFeatures(): array
    {
        return array_filter(self::cases(), fn ($f) => $f->type() === 'boolean');
    }

    /**
     * Get all quota features
     */
    public static function quotaFeatures(): array
    {
        return array_filter(self::cases(), fn ($f) => $f->type() === 'quota');
    }
}
