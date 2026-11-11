export interface Organization {
  id: number;
  name: string;
  slug: string;
  email?: string;
  phone?: string;
  address?: string;
  logo?: string;
  timezone: string;
  currency: string;
  status: 'active' | 'suspended' | 'cancelled';
  active_modules: string[];
  subscription?: Subscription;
}

export interface Plan {
  id: number;
  name: string;
  slug: string;
  description?: string;
  monthly_price: number;
  yearly_price: number;
  stripe_monthly_price_id?: string;
  stripe_yearly_price_id?: string;
  is_active: boolean;
  modules: Module[];
}

export interface Module {
  id: number;
  key: string;
  name: string;
  description?: string;
  monthly_price: number;
  yearly_price: number;
  is_active: boolean;
  features?: string[];
}

export interface Subscription {
  id: number;
  plan: Plan;
  stripe_status: string;
  billing_cycle: 'monthly' | 'yearly';
  trial_ends_at?: string;
  ends_at?: string;
}

export interface PaginatedData<T> {
  data: T[];
  current_page: number;
  last_page: number;
  per_page: number;
  total: number;
  links: { url: string | null; label: string; active: boolean }[];
}
