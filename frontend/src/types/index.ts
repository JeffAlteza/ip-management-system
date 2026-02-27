export interface User {
  id: number;
  name: string;
  email: string;
  role: "user" | "super_admin";
}

export interface Ip {
  id: number;
  ip_address: string;
  label: string;
  comment: string | null;
  created_by: number;
  created_at: string;
  updated_at: string;
}

export interface AuditLog {
  id: number;
  user_id: number;
  action: string;
  entity_type: string;
  entity_id: number;
  old_values: Record<string, unknown> | null;
  new_values: Record<string, unknown> | null;
  session_id: string | null;
  ip_address_value: string | null;
  created_at: string;
}

export interface ApiResponse<T> {
  success: boolean;
  message: string;
  data: T;
}

export interface PaginatedData<T> {
  current_page: number;
  data: T[];
  last_page: number;
  per_page: number;
  total: number;
}

export interface TokenData {
  accessToken: string;
  user: User;
  expiresIn: number;
  sessionId: string;
}
