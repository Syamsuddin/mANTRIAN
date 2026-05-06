<?php

namespace App\Services;

use App\Models\AuditLog;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuditLogger
{
    public function log(string $action, Model|string $entity, ?int $entityId = null, array $metadata = [], ?Request $request = null): void
    {
        $entityType = is_string($entity) ? $entity : $entity::class;
        $entityId ??= $entity instanceof Model ? $entity->getKey() : null;

        AuditLog::create([
            'actor_id' => Auth::id(),
            'action' => $action,
            'entity_type' => class_basename($entityType),
            'entity_id' => $entityId,
            'ip_address' => $request?->ip(),
            'user_agent' => $request ? substr((string) $request->userAgent(), 0, 255) : null,
            'metadata' => $metadata,
        ]);
    }
}
