"use client";

import { useState, useEffect, useCallback } from "react";
import { useRouter } from "next/navigation";
import { api } from "@/lib/api";
import { useAuth } from "@/lib/auth-context";
import { Button } from "@/components/ui/button";
import { Input } from "@/components/ui/input";
import { Label } from "@/components/ui/label";
import {
  Select,
  SelectContent,
  SelectItem,
  SelectTrigger,
  SelectValue,
} from "@/components/ui/select";
import { AuditLogTable } from "@/components/audit-log-table";
import { toast } from "sonner";
import type { AuditLog, ApiResponse, PaginatedData } from "@/types";

const ACTIONS = [
  { value: "all", label: "All Actions" },
  { value: "user_login", label: "Login" },
  { value: "user_logout", label: "Logout" },
  { value: "user_registered", label: "Registered" },
  { value: "ip_created", label: "IP Created" },
  { value: "ip_updated", label: "IP Updated" },
  { value: "ip_deleted", label: "IP Deleted" },
];

export default function AuditLogsPage() {
  const { user } = useAuth();
  const router = useRouter();
  const [logs, setLogs] = useState<AuditLog[]>([]);
  const [loading, setLoading] = useState(true);
  const [action, setAction] = useState("all");
  const [userId, setUserId] = useState("");
  const [sessionId, setSessionId] = useState("");
  const [dateFrom, setDateFrom] = useState("");
  const [dateTo, setDateTo] = useState("");

  useEffect(() => {
    if (user && user.role !== "super_admin") {
      router.replace("/dashboard");
    }
  }, [user, router]);

  const fetchLogs = useCallback(async () => {
    setLoading(true);
    try {
      const params = new URLSearchParams();
      if (action !== "all") params.set("filter[action]", action);
      if (userId) params.set("filter[user_id]", userId);
      if (sessionId) params.set("filter[session_id]", sessionId);
      if (dateFrom) params.set("filter[date_from]", dateFrom);
      if (dateTo) params.set("filter[date_to]", dateTo);

      const query = params.toString() ? `?${params.toString()}` : "";
      const result = await api.get<ApiResponse<PaginatedData<AuditLog>>>(
        `/audit-logs${query}`
      );

      if (result.success) {
        setLogs(result.data.data);
      }
    } catch {
      toast.error("Failed to load audit logs");
    } finally {
      setLoading(false);
    }
  }, [action, userId, sessionId, dateFrom, dateTo]);

  useEffect(() => {
    if (user?.role === "super_admin") {
      fetchLogs();
    }
  }, [user, fetchLogs]);

  if (user?.role !== "super_admin") return null;

  return (
    <div className="space-y-4">
      <h1 className="text-2xl font-semibold">Audit Logs</h1>

      <div className="flex flex-wrap items-end gap-3">
        <div className="space-y-1">
          <Label>Action</Label>
          <Select value={action} onValueChange={setAction}>
            <SelectTrigger className="w-[160px]">
              <SelectValue />
            </SelectTrigger>
            <SelectContent>
              {ACTIONS.map((a) => (
                <SelectItem key={a.value} value={a.value}>
                  {a.label}
                </SelectItem>
              ))}
            </SelectContent>
          </Select>
        </div>
        <div className="space-y-1">
          <Label>User ID</Label>
          <Input
            className="w-[120px]"
            placeholder="e.g. 1"
            value={userId}
            onChange={(e) => setUserId(e.target.value)}
          />
        </div>
        <div className="space-y-1">
          <Label>Session ID</Label>
          <Input
            className="w-[200px]"
            placeholder="e.g. abc123..."
            value={sessionId}
            onChange={(e) => setSessionId(e.target.value)}
          />
        </div>
        <div className="space-y-1">
          <Label>From</Label>
          <Input
            type="date"
            className="w-[160px]"
            value={dateFrom}
            onChange={(e) => setDateFrom(e.target.value)}
          />
        </div>
        <div className="space-y-1">
          <Label>To</Label>
          <Input
            type="date"
            className="w-[160px]"
            value={dateTo}
            onChange={(e) => setDateTo(e.target.value)}
          />
        </div>
        <Button onClick={fetchLogs} variant="outline">
          Filter
        </Button>
      </div>

      {loading ? (
        <p className="text-muted-foreground">Loading...</p>
      ) : (
        <AuditLogTable logs={logs} />
      )}
    </div>
  );
}
