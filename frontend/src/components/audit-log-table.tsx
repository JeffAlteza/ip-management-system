"use client";

import {
  Table,
  TableBody,
  TableCell,
  TableHead,
  TableHeader,
  TableRow,
} from "@/components/ui/table";
import { Badge } from "@/components/ui/badge";
import type { AuditLog } from "@/types";

interface AuditLogTableProps {
  logs: AuditLog[];
}

const ACTION_COLORS: Record<string, string> = {
  user_login: "bg-blue-100 text-blue-800",
  user_logout: "bg-gray-100 text-gray-800",
  user_registered: "bg-green-100 text-green-800",
  ip_created: "bg-green-100 text-green-800",
  ip_updated: "bg-yellow-100 text-yellow-800",
  ip_deleted: "bg-red-100 text-red-800",
};

function formatChanges(
  oldValues: Record<string, unknown> | null,
  newValues: Record<string, unknown> | null
) {
  if (!oldValues && !newValues) return "—";

  const changes: string[] = [];

  if (newValues && !oldValues) {
    Object.entries(newValues).forEach(([key, val]) => {
      changes.push(`${key}: ${val}`);
    });
  }

  if (oldValues && newValues) {
    Object.keys(newValues).forEach((key) => {
      if (String(oldValues[key]) !== String(newValues[key])) {
        changes.push(`${key}: ${oldValues[key]} → ${newValues[key]}`);
      }
    });
  }

  if (oldValues && !newValues) {
    Object.entries(oldValues).forEach(([key, val]) => {
      changes.push(`${key}: ${val}`);
    });
  }

  return changes.length > 0 ? changes.join(", ") : "—";
}

export function AuditLogTable({ logs }: AuditLogTableProps) {
  return (
    <div className="rounded-md border">
      <Table>
        <TableHeader>
          <TableRow>
            <TableHead>Action</TableHead>
            <TableHead>Entity</TableHead>
            <TableHead>User ID</TableHead>
            <TableHead>Changes</TableHead>
            <TableHead>Session</TableHead>
            <TableHead>Date</TableHead>
          </TableRow>
        </TableHeader>
        <TableBody>
          {logs.length === 0 ? (
            <TableRow>
              <TableCell colSpan={6} className="text-center text-muted-foreground">
                No audit logs found
              </TableCell>
            </TableRow>
          ) : (
            logs.map((log) => (
              <TableRow key={log.id}>
                <TableCell>
                  <Badge
                    variant="secondary"
                    className={ACTION_COLORS[log.action] || ""}
                  >
                    {log.action}
                  </Badge>
                </TableCell>
                <TableCell>
                  {log.entity_type}#{log.entity_id}
                </TableCell>
                <TableCell>{log.user_id}</TableCell>
                <TableCell className="max-w-xs truncate text-sm">
                  {formatChanges(log.old_values, log.new_values)}
                </TableCell>
                <TableCell className="font-mono text-xs">
                  {log.session_id ? log.session_id.slice(0, 8) + "..." : "—"}
                </TableCell>
                <TableCell>
                  {new Date(log.created_at).toLocaleString()}
                </TableCell>
              </TableRow>
            ))
          )}
        </TableBody>
      </Table>
    </div>
  );
}
