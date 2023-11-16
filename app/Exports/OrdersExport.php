<?php

namespace App\Exports;

use App\Order;
use App\Product;
use App\ProductStock;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class OrdersExport implements FromCollection, WithHeadings, ShouldAutoSize
{
    use Exportable;

    /**
     * @return \Illuminate\Support\Collection
     */

    public function collection()
    {
        $ids = Product::allByRole()->pluck('id');
        $orders = Order::whereIn('product_id', $ids)->get();
        $hasRole = Auth::user()->hasRole(['administrator', 'admin']);

        $collect = [];
        foreach ($orders as $order) {
            $qty = explode(',', $order->product_stock_ids);
            $stock = ProductStock::find($qty[0]);
            $subTotal = $order->total;
            // foreach ($qty as $id) {
            //     $subTotal += ProductStock::find($id)->discountPrice;
            // }
            $collect[] = [
                'OrderID' => $order->reference_number,
                'Product' => $order->product->name,
                'Purchase' => $stock['purchase'],
                'Sale' => $stock['discountPrice'],
                'Quantity' => sizeof($qty),
                // 'Coupon' => $order->coupon_id ? $order->coupon->name : "No Coupon",
                'Sub Total' => $subTotal,
                'Shipping Charges' => $hasRole ? $order->shipping_charges : 0,
                // 'Grand Total' => Auth::user()->role_id == 3 ? $order->total : $order->grand_total,
                'Grand Total' => $order->whereReferenceNumber($order->reference_number)->whereIn('product_id', $ids)->sum('total') + $order->shipping_charges,
                'Profit' => $stock['profit'] * sizeof($qty),
                'Status' => $order->status,
                'Payment Method' => $order->payment_method,
                'Order Date' => $order->created_at->format('d-m-Y'),
                'Shipped Date' => $order->shipped_date ? $order->shipped_date->format('m-d-Y') : "",
                'Delivered Date' => $order->delivered_date ? $order->delivered_date->format('m-d-Y') : "",
            ];
        }

        return collect($collect);
    }

    public function headings(): array
    {
        return [
            'OrderID',
            'Product',
            'Purchase',
            'Sale',
            'Quantity',
            // 'Coupon',
            'Sub Total',
            'Shipping Charges',
            'Grand Total',
            'Profit',
            'Status',
            'Payment Method',
            'Order Date',
            'Shipped Date',
            'Delivered Date',
        ];
    }
}
