<?php

namespace App\Enums;

enum UserRole: string
{
    case Admin = 'Admin';
    case Leader = 'Leader';
    case Academic = 'Academic';
    case ProjectLeader = 'ProjectLeader';

    public function canonicalValue(): string
    {
        return $this === self::ProjectLeader ? self::Leader->value : $this->value;
    }

    public function dashboardRouteName(): string
    {
        return match ($this) {
            self::Admin => 'admin.dashboard',
            self::Leader, self::ProjectLeader => 'leader.dashboard',
            self::Academic => 'academic.dashboard',
        };
    }

    public function label(): string
    {
        return match ($this) {
            self::Admin => 'Admin',
            self::Leader, self::ProjectLeader => 'Project Leader',
            self::Academic => 'Academic',
        };
    }

    public function matches(self|string $role): bool
    {
        return $this->canonicalValue() === self::normalize($role);
    }

    public static function normalize(self|string $role): string
    {
        if ($role instanceof self) {
            return $role->canonicalValue();
        }

        return match ($role) {
            self::ProjectLeader->value => self::Leader->value,
            default => $role,
        };
    }

    public static function assignable(): array
    {
        return [self::Admin, self::Leader, self::Academic];
    }
}
