import { Head, Link, useForm } from '@inertiajs/react';
import AppLayout from '@/layouts/app-layout';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { Select, SelectContent, SelectItem, SelectTrigger, SelectValue } from '@/components/ui/select';
import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/card';
import type { Product, Category } from '@/types/inventory';

interface Props {
  product: Product;
  categories: Category[];
}

export default function EditProduct({ product, categories }: Props) {
  const { data, setData, put, processing, errors } = useForm({
    name: product.name, category_id: String(product.category?.id ?? ''),
    unit: product.unit, purchase_price: String(product.purchase_price),
    selling_price: String(product.selling_price), alert_quantity: String(product.alert_quantity),
    is_active: product.is_active,
  });

  const handleSubmit = (e: React.FormEvent) => {
    e.preventDefault();
    put(`/inventory/products/${product.id}`);
  };

  return (
    <AppLayout breadcrumbs={[
      { title: 'Products', href: '/inventory/products' },
      { title: 'Edit', href: `/inventory/products/${product.id}/edit` },
    ]}>
      <Head title={`Edit ${product.name}`} />
      <div className="max-w-2xl mx-auto">
        <Card>
          <CardHeader><CardTitle>Edit {product.name}</CardTitle></CardHeader>
          <CardContent>
            <form onSubmit={handleSubmit} className="space-y-4">
              <div className="grid grid-cols-2 gap-4">
                <div className="space-y-2">
                  <Label>Name</Label>
                  <Input value={data.name} onChange={(e) => setData('name', e.target.value)} />
                  {errors.name && <p className="text-sm text-destructive">{errors.name}</p>}
                </div>
                <div className="space-y-2">
                  <Label>SKU</Label>
                  <Input value={product.sku ?? ''} disabled />
                </div>
              </div>
              <div className="grid grid-cols-2 gap-4">
                <div className="space-y-2">
                  <Label>Category</Label>
                  <Select value={data.category_id} onValueChange={(v) => setData('category_id', v)}>
                    <SelectTrigger><SelectValue placeholder="Select category" /></SelectTrigger>
                    <SelectContent>
                      {categories.map((cat) => (<SelectItem key={cat.id} value={String(cat.id)}>{cat.name}</SelectItem>))}
                    </SelectContent>
                  </Select>
                </div>
                <div className="space-y-2">
                  <Label>Unit</Label>
                  <Select value={data.unit} onValueChange={(v) => setData('unit', v)}>
                    <SelectTrigger><SelectValue /></SelectTrigger>
                    <SelectContent>
                      <SelectItem value="pcs">Pieces</SelectItem>
                      <SelectItem value="kg">Kilogram</SelectItem>
                      <SelectItem value="ltr">Liter</SelectItem>
                      <SelectItem value="box">Box</SelectItem>
                    </SelectContent>
                  </Select>
                </div>
              </div>
              <div className="grid grid-cols-2 gap-4">
                <div className="space-y-2">
                  <Label>Purchase Price</Label>
                  <Input type="number" step="0.01" value={data.purchase_price} onChange={(e) => setData('purchase_price', e.target.value)} />
                </div>
                <div className="space-y-2">
                  <Label>Selling Price</Label>
                  <Input type="number" step="0.01" value={data.selling_price} onChange={(e) => setData('selling_price', e.target.value)} />
                </div>
              </div>
              <div className="grid grid-cols-2 gap-4">
                <div className="space-y-2">
                  <Label>Alert Quantity</Label>
                  <Input type="number" value={data.alert_quantity} onChange={(e) => setData('alert_quantity', e.target.value)} />
                </div>
                <div className="space-y-2 flex items-end pb-2">
                  <Label className="flex items-center gap-2">
                    <input type="checkbox" checked={data.is_active} onChange={(e) => setData('is_active', e.target.checked)} />
                    Active
                  </Label>
                </div>
              </div>
              <div className="flex gap-4 pt-2">
                <Button type="submit" disabled={processing}>Update Product</Button>
                <Link href="/inventory/products"><Button variant="outline" type="button">Cancel</Button></Link>
              </div>
            </form>
          </CardContent>
        </Card>
      </div>
    </AppLayout>
  );
}
