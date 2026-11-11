import { Head, Link, useForm } from '@inertiajs/react';
import AppLayout from '@/layouts/app-layout';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { Select, SelectContent, SelectItem, SelectTrigger, SelectValue } from '@/components/ui/select';
import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/card';
import type { Category } from '@/types/inventory';

interface Props {
  categories: Category[];
}

export default function CreateProduct({ categories }: Props) {
  const { data, setData, post, processing, errors } = useForm({
    name: '', sku: '', category_id: '', unit: 'pcs',
    purchase_price: '0', selling_price: '0', alert_quantity: '5',
  });

  const handleSubmit = (e: React.FormEvent) => {
    e.preventDefault();
    post('/inventory/products');
  };

  return (
    <AppLayout breadcrumbs={[
      { title: 'Products', href: '/inventory/products' },
      { title: 'Create', href: '/inventory/products/create' },
    ]}>
      <Head title="Create Product" />
      <div className="max-w-2xl mx-auto">
        <Card>
          <CardHeader><CardTitle>Create Product</CardTitle></CardHeader>
          <CardContent>
            <form onSubmit={handleSubmit} className="space-y-4">
              <div className="grid grid-cols-2 gap-4">
                <div className="space-y-2">
                  <Label htmlFor="name">Name</Label>
                  <Input id="name" value={data.name} onChange={(e) => setData('name', e.target.value)} />
                  {errors.name && <p className="text-sm text-destructive">{errors.name}</p>}
                </div>
                <div className="space-y-2">
                  <Label htmlFor="sku">SKU</Label>
                  <Input id="sku" value={data.sku} onChange={(e) => setData('sku', e.target.value)} />
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
                  <Label htmlFor="purchase_price">Purchase Price</Label>
                  <Input id="purchase_price" type="number" step="0.01" value={data.purchase_price} onChange={(e) => setData('purchase_price', e.target.value)} />
                  {errors.purchase_price && <p className="text-sm text-destructive">{errors.purchase_price}</p>}
                </div>
                <div className="space-y-2">
                  <Label htmlFor="selling_price">Selling Price</Label>
                  <Input id="selling_price" type="number" step="0.01" value={data.selling_price} onChange={(e) => setData('selling_price', e.target.value)} />
                  {errors.selling_price && <p className="text-sm text-destructive">{errors.selling_price}</p>}
                </div>
              </div>
              <div className="space-y-2">
                <Label htmlFor="alert_quantity">Alert Quantity</Label>
                <Input id="alert_quantity" type="number" value={data.alert_quantity} onChange={(e) => setData('alert_quantity', e.target.value)} />
              </div>
              <div className="flex gap-4 pt-2">
                <Button type="submit" disabled={processing}>Create Product</Button>
                <Link href="/inventory/products"><Button variant="outline" type="button">Cancel</Button></Link>
              </div>
            </form>
          </CardContent>
        </Card>
      </div>
    </AppLayout>
  );
}
