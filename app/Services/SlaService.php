<?php

namespace App\Services;

use App\Models\Type;
use App\Models\Category;
use Carbon\Carbon;
use Illuminate\Support\Facades\Cache;

class SlaService
{
    public static function resolve(string $typeName = null, string $categoryName = null): array
    {
        // 1) tenta por TYPE
        if ($typeName) {
            $type = Cache::remember("type_sla:{$typeName}", 600, fn() =>
            Type::where('nome', $typeName)->first(['default_priority','sla_hours'])
            );
            if ($type && $type->default_priority) {
                $priority = $type->default_priority;
                $hours = $type->sla_hours ?? self::defaultHoursByPriority($priority);
                return compact('priority','hours');
            }
        }

        // 2) tenta por CATEGORY
        if ($categoryName) {
            $cat = Cache::remember("cat_sla:{$categoryName}", 600, fn() =>
            Category::where('nome', $categoryName)->first(['default_priority','sla_hours'])
            );
            if ($cat && $cat->default_priority) {
                $priority = $cat->default_priority;
                $hours = $cat->sla_hours ?? self::defaultHoursByPriority($priority);
                return compact('priority','hours');
            }
        }

        // 3) fallback global
        $priority = config('itil.defaults.priority', 'media');
        $hours    = self::defaultHoursByPriority($priority);
        return compact('priority','hours');
    }

    public static function defaultHoursByPriority(string $priority): int
    {
        $map = config('itil.priority_targets_hours', [
            'muito alta' => 4,
            'alta'       => 8,
            'media'      => 24,
            'baixa'      => 72,
        ]);
        return (int)($map[$priority] ?? 24);
    }

    public static function dueAt(string $priority, Carbon $createdAt, ?int $overrideHours = null): Carbon
    {
        $hours = $overrideHours ?? self::defaultHoursByPriority($priority);
        return (clone $createdAt)->addHours($hours);
    }

    // Helpers pra invalidar cache ao salvar Type/Category
    public static function forgetType(string $name): void { Cache::forget("type_sla:{$name}"); }
    public static function forgetCategory(string $name): void { Cache::forget("cat_sla:{$name}"); }
}
