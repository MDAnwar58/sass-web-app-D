import React, { useState } from 'react';
import { Head, Link, router } from '@inertiajs/react';
import AppLayout from '@/layouts/app-layout';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Badge } from '@/components/ui/badge';
import {
  Table, TableBody, TableCell, TableHead,
  TableHeader, TableRow
} from '@/components/ui/table';
import { Plus, Search, Edit, Trash2 } from 'lucide-react';
import type { PaginatedData } from '@/types/core';
import type { Product } from '@/types/inventory';

interface Props {
  products: PaginatedData<Product>;
  filters: { search?: string };
}

export default function ProductsIndex({ products, filters }: Props) {
  const [search, setSearch] = useState(filters.search ?? '');

  const handleSearch = (e: React.FormEvent) => {
    e.preventDefault();
    router.get('/inventory/products', { search }, { preserveState: true });
  };

  const handleDelete = (id: number) => {
    if (confirm('Delete this product?')) {
      router.delete(`/inventory/products/${id}`);
    }
  };

  return (
    <AppLayout breadcrumbs={[{ title: 'Products', href: '/inventory/products' }]}>
      <Head title="Products" />
      <div className="space-y-6">
        <div className="flex items-center justify-between">
          <div>
            <h1 className="text-2xl font-bold">Products</h1>
            <p className="text-muted-foreground">{products.total} total products</p>
          </div>
          <Link href="/inventory/products/create">
            <Button><Plus size={16} className="mr-2" />Add Product</Button>
          </Link>
        </div>

        <form onSubmit={handleSearch} className="flex gap-2 max-w-sm">
          <Input placeholder="Search by name or SKU..." value={search} onChange={(e) => setSearch(e.target.value)} />
          <Button type="submit" variant="outline"><Search size={16} /></Button>
        </form>

        <div className="border rounded-lg">
          <Table>
            <TableHeader>
              <TableRow>
                <TableHead>Name</TableHead>
                <TableHead>SKU</TableHead>
                <TableHead>Category</TableHead>
                <TableHead>Stock</TableHead>
                <TableHead>Sell Price</TableHead>
                <TableHead>Status</TableHead>
                <TableHead className="text-right">Actions</TableHead>
              </TableRow>
            </TableHeader>
            <TableBody>
              {products.data.map((product) => (
                <TableRow key={product.id}>
                  <TableCell className="font-medium">{product.name}</TableCell>
                  <TableCell className="text-muted-foreground">{product.sku ?? '—'}</TableCell>
                  <TableCell>{product.category?.name ?? '—'}</TableCell>
                  <TableCell>
                    <span className={product.stock_quantity <= product.alert_quantity ? 'text-destructive font-semibold' : ''}>
                      {product.stock_quantity} {product.unit}
                    </span>
                  </TableCell>
                  <TableCell>${Number(product.selling_price).toFixed(2)}</TableCell>
                  <TableCell>
                    <Badge variant={product.is_active ? 'default' : 'secondary'}>
                      {product.is_active ? 'Active' : 'Inactive'}
                    </Badge>
                  </TableCell>
                  <TableCell className="text-right">
                    <div className="flex justify-end gap-2">
                      <Link href={`/inventory/products/${product.id}/edit`}>
                        <Button variant="ghost" size="sm"><Edit size={14} /></Button>
                      </Link>
                      <Button variant="ghost" size="sm" onClick={() => handleDelete(product.id)} className="text-destructive">
                        <Trash2 size={14} />
                      </Button>
                    </div>
                  </TableCell>
                </TableRow>
              ))}
              {products.data.length === 0 && (
                <TableRow>
                  <TableCell colSpan={7} className="text-center text-muted-foreground py-12">
                    No products found.
                  </TableCell>
                </TableRow>
              )}
            </TableBody>
          </Table>
        </div>

        <div className="flex gap-1">
          {products.links.map((link, i) => (
            <Link key={i} href={link.url ?? '#'}>
              <Button variant={link.active ? 'default' : 'outline'} size="sm" disabled={!link.url}
                dangerouslySetInnerHTML={{ __html: link.label }} />
            </Link>
          ))}
        </div>
      </div>
    </AppLayout>
  );
}
