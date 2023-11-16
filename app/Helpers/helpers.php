<?php

use Illuminate\Support\Facades\DB;

function flash($type = null, $message = null)
{
	session()->flash($type, $message);
}

function __take($take = null)
{
	$input = request('take');
	return is_numeric($input) ? $input : ($input == 'all' ? '' : $take);
}

function __model($modelName = null)
{
	return '\\App\\'.$modelName;
}

function __all($modelName = null)
{
	$model = '\\App\\'.$modelName;
	return $model::all();
}

function __siteMeta($name)
{
	return \App\SiteOption::where('key', $name)->pluck('value')->first();
}

function __metaTag($name)
{
	// dd(request()->id);
	// dd((new \App\MetaTag)->getKeyName());
	$str = str_replace(request()->id, '*', request()->path());
	$query1 = \App\MetaTag::whereSlug(request()->path())->first()[$name];
	$query2 = \App\MetaTag::where('slug', 'LIKE', "%{$str}%")->first()[$name];
	return $query1 ?: $query2 ?: \App\MetaTag::first()[$name];
}

function __products($type = null, $publish = 1, $order = 'desc')
{
	return \App\User::withoutTrashed()->with(['products' => function ($query) use ($type, $publish, $order) {
		$type ? $query->whereType($type) : $query;
		$publish == 'all' ? $query : $query->wherePublish($publish);
		$query->orderBy('created_at', $order);
	}])->get()->map->products->collapse();

	// return User::withoutTrashed()->with('products')->get()->map->products->collapse();
}
function __productsOrderByAsc($type = null, $publish = 1)
{
	return __products($type, $publish, 'asc');
}

function __getPhoto($folder = null, $filename = null, $thumbnail = false)
{
	$folder = ltrim(rtrim($folder, '/'), '/');
	return $folder && $filename ? $thumbnail 
	? asset("uploads/{$folder}/".$thumbnail."/" . $filename) 
	: asset("uploads/{$folder}/" . $filename) : false;
}

function __getWithRole($modelName = null)
{
	$model = '\\App\\'.$modelName;
	return $model::all();
}

function saveManyWithRequestModelRelation($request, $model, $relation)
{
    // dd($request->all());
	$name = \Str::singular($relation);
	$relation = \Str::plural($relation);
	foreach ($request->$name['id'] as $index => $id)
	{
		$row = $model->$relation()->updateOrCreate(['id'=>$id], ['user_id'=>auth()->id()]);
		foreach (array_keys($request->$name) as $colName)
		{
			if ($colName !== 'id') {
				$row->$colName = $request->$name[$colName][$index];
			}
		}
		$rows[] = $row;
	}
	$model->$relation()->saveMany($rows);
}


function __cartCount()
{
    return \Gloudemans\Shoppingcart\Facades\Cart::content()->count();
}

function __cartSubTotal()
{
    return number_format(\Gloudemans\Shoppingcart\Facades\Cart::subtotal(),2);
}

function __getDiscountAmount($discount)
{

    $total = (int) str_replace( ',', '',\Gloudemans\Shoppingcart\Facades\Cart::total());
    return $total - ($total/100) * $discount;
}

function __getDiscountPrice($id)
{
    $product = \App\ProductStock::find($id);
    return $product->sale - ($product->sale * $product->discount) / 100;
}

function __getOrderCount()
{
     $orderCount = DB::table('orders')
        ->select('reference_number', DB::raw('count(*) as total'))
        ->groupBy('reference_number')
        ->get();
     return count($orderCount);
}

function getRatedProduct($id)
{
    return ceil(DB::table('reviews')
        ->where('product_id', $id)
        ->groupBy('product_id')
        ->avg('rating'));
}

function __changeStockStatus($id, $status)
{
    \App\ProductStock::findOrFail($id)->update(['status' => $status]);
}