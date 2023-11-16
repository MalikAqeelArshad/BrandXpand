<?php

namespace App\Traits;

use App\Product;

trait FiltersTrait {

    public function hasCol($column)
    {
        // dd($this->getConnection()->getSchemaBuilder()->getColumnListing($this->getTable()));
        return $this->getConnection()->getSchemaBuilder()->hasColumn($this->getTable(), $column);
    }

    public function scopeFilters($query)
    {
        if (request('user')) { $query->userId(request('user')); }
        if (request('status')) { $query->status(request('status')); }
        if (request('brand')) { $query->brandIds(request('brand')); }
        if (request('category')) { $query->categoryId(request('category')); }
        if (request('product')) { request('product') == 'empty' ? $query->emptyStock(request('product')) : $query->productId(request('product')); }
        if (request('from') || request('to')) { $query->dateRange(request('from'),request('to')); }

        return $query->orderBy(request('orderBy') ?? 'created_at', request('order') ?? 'desc');

        // return $query->latest();
    }

    public function scopeUserId($query, $id)
    {
        return (int) $id && $this->hasCol('user_id') ? $query->whereUserId($id) : $query;
    }

    public function scopeStatus($query, $status)
    {
        return in_array($status, ['sold','unsold','booked','damaged']) ? $query->whereStatus($status) : $query;
    }

    public function scopeEmptyStock($query, $stock)
    {
        return $stock == 'empty' && $this->table == 'products' ? $query->whereDoesntHave('stocks', function ($q) {
            $q->whereStatus('unsold');
        }) : $query;
    }

    public function scopeProductId($query, $id)
    {
        // return $id && $id != 'all' && $this->hasCol('product_id') ? $query->whereProductId($id) : $query;
        return (int) $id ? $query->where($this->table == 'products' ? 'id' : 'product_id', $id) : $query;
    }

    public function scopeCategoryId($query, $id)
    {
        return (int) $id && $this->hasCol('category_id') ? $query->whereCategoryId($id) : $query;
    }

    public function scopeBrandIds($query, $ids)
    {
        return $this->hasCol('brand_id') ? $query->whereIn('brand_id', $ids) : $query;
    }

    public function scopeDateRange($query, $from, $to)
    {
        if ($from && $to && ($from != $to)) {
            $query->whereDate('updated_at', '>=', $from);
            $query->whereDate('updated_at', '<=', $to);
            // $query->whereBetween('updated_at', [$from, $to]);
        } elseif ($from == $to) {
            $query->whereDate('updated_at', $from);
        } else {
            if ($from) { $query->where('updated_at', '>=', $from); }
            if ($to) { $query->where('updated_at', '<=', $to); }
        }
        return $query;
    }

    public function scopeTotalProfit($query, $userId = null, $productId = null)
    {
        $isAdmin = auth()->user()->hasRole(['administrator','admin']);

        if ($isAdmin && $userId && $productId) {
            $products = Product::where(['id'=>$productId, 'user_id'=>$userId]);
        } elseif ($isAdmin && $userId) {
            $products = Product::where('user_id', $userId);
        } elseif ($isAdmin) {
            $products = Product::all();
        } elseif ($productId) {
            $products = Product::where(['id'=>$productId, 'user_id'=>auth()->id()]);
        } else {
            $products = Product::where('user_id', auth()->id());
        }

        $query->whereIn('product_id', $products->pluck('id'))->whereStatus('sold');

        $profit = 0;
        foreach ($query->get() as $stock) { $profit += $stock->profit; }

        return $profit;
    }
}