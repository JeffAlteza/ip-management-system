"use client";

import { useState, useEffect, type FormEvent } from "react";
import {
  Dialog,
  DialogContent,
  DialogHeader,
  DialogTitle,
} from "@/components/ui/dialog";
import { Button } from "@/components/ui/button";
import { Input } from "@/components/ui/input";
import { Label } from "@/components/ui/label";
import { toast } from "sonner";
import { api } from "@/lib/api";
import type { Ip, ApiResponse } from "@/types";

const IP_REGEX =
  /^(?:(?:25[0-5]|2[0-4]\d|[01]?\d\d?)\.){3}(?:25[0-5]|2[0-4]\d|[01]?\d\d?)$|^([0-9a-fA-F]{1,4}:){7}[0-9a-fA-F]{1,4}$|^::$|^([0-9a-fA-F]{1,4}:){1,7}:$|^:(:([0-9a-fA-F]{1,4})){1,7}$|^([0-9a-fA-F]{1,4}:){1,6}:[0-9a-fA-F]{1,4}$/;

interface IpFormDialogProps {
  open: boolean;
  onOpenChange: (open: boolean) => void;
  ip?: Ip | null;
  onSuccess: () => void;
}

export function IpFormDialog({
  open,
  onOpenChange,
  ip,
  onSuccess,
}: IpFormDialogProps) {
  const [ipAddress, setIpAddress] = useState("");
  const [label, setLabel] = useState("");
  const [comment, setComment] = useState("");
  const [loading, setLoading] = useState(false);

  const isEditing = !!ip;

  useEffect(() => {
    if (ip) {
      setIpAddress(ip.ip_address);
      setLabel(ip.label);
      setComment(ip.comment || "");
    } else {
      setIpAddress("");
      setLabel("");
      setComment("");
    }
  }, [ip, open]);

  async function handleSubmit(e: FormEvent) {
    e.preventDefault();

    if (!IP_REGEX.test(ipAddress)) {
      toast.error("Please enter a valid IPv4 or IPv6 address");
      return;
    }

    setLoading(true);

    try {
      const payload = {
        ip_address: ipAddress,
        label,
        comment: comment || null,
      };

      if (isEditing) {
        const result = await api.put<ApiResponse<Ip>>(
          `/ips/${ip.id}`,
          payload
        );
        if (!result.success) throw new Error(result.message);
        toast.success("IP updated");
      } else {
        const result = await api.post<ApiResponse<Ip>>("/ips", payload);
        if (!result.success) throw new Error(result.message);
        toast.success("IP created");
      }

      onOpenChange(false);
      onSuccess();
    } catch (err) {
      toast.error(err instanceof Error ? err.message : "Something went wrong");
    } finally {
      setLoading(false);
    }
  }

  return (
    <Dialog open={open} onOpenChange={onOpenChange}>
      <DialogContent>
        <DialogHeader>
          <DialogTitle>{isEditing ? "Edit IP" : "Add IP"}</DialogTitle>
        </DialogHeader>
        <form onSubmit={handleSubmit} className="space-y-4">
          <div className="space-y-2">
            <Label htmlFor="ip_address">IP Address</Label>
            <Input
              id="ip_address"
              placeholder="192.168.1.1 or 2001:db8::1"
              value={ipAddress}
              onChange={(e) => setIpAddress(e.target.value)}
              required
            />
          </div>
          <div className="space-y-2">
            <Label htmlFor="label">Label</Label>
            <Input
              id="label"
              placeholder="e.g. Web Server"
              value={label}
              onChange={(e) => setLabel(e.target.value)}
              required
            />
          </div>
          <div className="space-y-2">
            <Label htmlFor="comment">Comment (optional)</Label>
            <Input
              id="comment"
              placeholder="Additional notes..."
              value={comment}
              onChange={(e) => setComment(e.target.value)}
            />
          </div>
          <div className="flex justify-end gap-2">
            <Button
              type="button"
              variant="outline"
              onClick={() => onOpenChange(false)}
            >
              Cancel
            </Button>
            <Button type="submit" disabled={loading}>
              {loading ? "Saving..." : isEditing ? "Update" : "Create"}
            </Button>
          </div>
        </form>
      </DialogContent>
    </Dialog>
  );
}
