<?php

namespace App\Filament\Clusters;

use App\Models\User;
use BackedEnum;
use Filament\Clusters\Cluster;

class AccessManagementCluster extends Cluster
{
    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-shield-check';

    public static function getNavigationLabel(): string
    {
        return __('filament.clusters.access_management.navigation_label');
    }

    public static function getClusterBreadcrumb(): string
    {
        return __('filament.clusters.access_management.breadcrumb');
    }

    public static function canAccessClusteredComponents(): bool
    {
        $user = auth()->user();

        if ($user instanceof User && $user->isPrimaryUser()) {
            return true;
        }

        return parent::canAccessClusteredComponents();
    }
}
