"use client";

import { useState, useEffect, useCallback } from "react";
import { api } from "@/lib/api";
import { Button } from "@/components/ui/button";
import { IpTable } from "@/components/ip-table";
import { IpFormDialog } from "@/components/ip-form-dialog";
import { toast } from "sonner";
import type { Ip, ApiResponse, PaginatedData } from "@/types";

export default function DashboardPage() {
  const [ips, setIps] = useState<Ip[]>([]);
  const [loading, setLoading] = useState(true);
  const [dialogOpen, setDialogOpen] = useState(false);
  const [editingIp, setEditingIp] = useState<Ip | null>(null);
  const [deleteConfirm, setDeleteConfirm] = useState<Ip | null>(null);

  const fetchIps = useCallback(async () => {
    try {
      const result = await api.get<ApiResponse<PaginatedData<Ip>>>("/ips");
      if (result.success) {
        setIps(result.data.data);
      }
    } catch {
      toast.error("Failed to load IP addresses");
    } finally {
      setLoading(false);
    }
  }, []);

  useEffect(() => {
    fetchIps();
  }, [fetchIps]);

  function handleEdit(ip: Ip) {
    setEditingIp(ip);
    setDialogOpen(true);
  }

  function handleAdd() {
    setEditingIp(null);
    setDialogOpen(true);
  }

  async function handleDelete(ip: Ip) {
    try {
      const result = await api.delete<ApiResponse<null>>(`/ips/${ip.id}`);
      if (!result.success) throw new Error(result.message);
      toast.success("IP deleted");
      setDeleteConfirm(null);
      fetchIps();
    } catch (err) {
      toast.error(err instanceof Error ? err.message : "Delete failed");
    }
  }

  if (loading) {
    return <p className="text-muted-foreground">Loading...</p>;
  }

  return (
    <div className="space-y-4">
      <div className="flex items-center justify-between">
        <h1 className="text-2xl font-semibold">IP Addresses</h1>
        <Button onClick={handleAdd}>Add IP</Button>
      </div>

      <IpTable
        ips={ips}
        onEdit={handleEdit}
        onDelete={(ip) => setDeleteConfirm(ip)}
      />

      <IpFormDialog
        open={dialogOpen}
        onOpenChange={setDialogOpen}
        ip={editingIp}
        onSuccess={fetchIps}
      />

      {deleteConfirm && (
        <div className="fixed inset-0 z-50 flex items-center justify-center bg-black/50">
          <div className="rounded-lg bg-white p-6 shadow-lg dark:bg-zinc-900">
            <h2 className="text-lg font-semibold">Confirm Delete</h2>
            <p className="mt-2 text-sm text-muted-foreground">
              Delete <span className="font-mono">{deleteConfirm.ip_address}</span> ({deleteConfirm.label})?
            </p>
            <div className="mt-4 flex justify-end gap-2">
              <Button variant="outline" onClick={() => setDeleteConfirm(null)}>
                Cancel
              </Button>
              <Button variant="destructive" onClick={() => handleDelete(deleteConfirm)}>
                Delete
              </Button>
            </div>
          </div>
        </div>
      )}
    </div>
  );
}
