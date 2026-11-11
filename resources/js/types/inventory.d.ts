export interface Product {
  id: number;
  name: string;
  sku?: string;
  barcode?: string;
  unit: string;
  purchase_price: number;
  selling_price: number;
  stock_quantity: number;
  alert_quantity: number;
  category?: Category;
  is_active: boolean;
}

export interface Category {
  id: number;
  name: string;
  slug: string;
  parent_id?: number;
  children?: Category[];
  products_count?: number;
}

export interface Supplier {
  id: number;
  name: string;
  email?: string;
  phone?: string;
  company?: string;
  address?: string;
  payable: number;
  purchases_count?: number;
}

export interface Customer {
  id: number;
  name: string;
  email?: string;
  phone?: string;
  address?: string;
  receivable: number;
  sales_count?: number;
}

export interface Sale {
  id: number;
  invoice_number: string;
  sale_date: string;
  subtotal: number;
  discount: number;
  tax: number;
  total: number;
  paid: number;
  payment_status: 'paid' | 'partial' | 'unpaid';
  payment_method: string;
  customer?: Customer;
  items: SaleItem[];
}

export interface SaleItem {
  id: number;
  product: Product;
  quantity: number;
  unit_price: number;
  discount: number;
  subtotal: number;
}

export interface Purchase {
  id: number;
  reference: string;
  purchase_date: string;
  subtotal: number;
  discount: number;
  tax: number;
  total: number;
  paid: number;
  payment_status: 'paid' | 'partial' | 'unpaid';
  supplier?: Supplier;
  items: PurchaseItem[];
}

export interface PurchaseItem {
  id: number;
  product: Product;
  quantity: number;
  unit_price: number;
  subtotal: number;
}

export interface StockMovement {
  id: number;
  type: 'in' | 'out' | 'adjustment';
  quantity: number;
  stock_before: number;
  stock_after: number;
  note?: string;
  product?: Product;
  created_at: string;
}
