<?php

namespace App\Http\Controllers;

use App\CategoryPost;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;

session_start();

class ProductController extends Controller
{
    public function AuthCheck()
    {
        $admin_id = Session::get('admin_id');
        if ($admin_id) {
            return Redirect::to('dashboard');
        } else {
            return Redirect::to('admin')->send();
        }
    }

    public function add_product()
    {
        $this->AuthCheck();
        $cate_product = DB::table('tbl_category_product')
            ->orderBy('category_id', 'asc')
            ->get();
        $brand_product = DB::table('tbl_brand')
            ->orderBy('brand_id', 'asc')
            ->get();

        return view('admin.product.add_product')
            ->with('cate_product', $cate_product)
            ->with('brand_product', $brand_product);
    }

    public function show_product()
    {
        $this->AuthCheck();
        $all_product = DB::table('tbl_product')
            ->join('tbl_category_product', 'tbl_category_product.category_id', '=', 'tbl_product.category_id')
            ->join('tbl_brand', 'tbl_brand.brand_id', '=', 'tbl_product.brand_id')
            ->orderBy('tbl_product.product_id', 'asc')->paginate(10);
        $manager_product = view('admin.product.show_product')->with('all_product', $all_product);

        return view('admin_layout')->with('admin.product.show_product', $manager_product);
    }

    public function save_product(Request $request)
    {
        $this->AuthCheck();
        $data = array();
        $data['product_name'] = $request->product_name;
        $data['product_price'] = $request->product_price;
        $data['product_color'] = $request->product_color;
        $data['product_desc'] = $request->product_desc;
        $data['product_content'] = $request->product_content;
        $data['category_id'] = $request->product_cate;
        $data['brand_id'] = $request->product_brand;
        $data['product_status'] = $request->product_status;
        $get_image = $request->file('product_image');
        if ($get_image) {
            $get_name_image = $get_image->getClientOriginalName();
            $name_image = current(explode('.', $get_name_image));
            $new_image = $name_image . time() . '.' . $get_image->getClientOriginalExtension();
            $get_image->move('public/uploads/product', $new_image);
            $data['product_image'] = $new_image;
            DB::table('tbl_product')->insert($data);
            Session::put('message', 'Thêm sản phẩm thành công');

            return Redirect::to('show-product');
        }
        $data['product_image'] = '';
        DB::table('tbl_product')->insert($data);
        Session::put('message', 'Thêm sản phẩm thành công');

        return Redirect::to('show-product');
    }

    public function active_product($product_id)
    {
        $this->AuthCheck();
        DB::table('tbl_product')
            ->where('product_id', $product_id)
            ->update(['product_status' => 1]);
        Session::put('message', 'Hiển thị sản phẩm thành công');

        return Redirect::to('show-product');
    }

    public function unactive_product($product_id)
    {
        $this->AuthCheck();
        DB::table('tbl_product')
            ->where('product_id', $product_id)
            ->update(['product_status' => 0]);
        Session::put('message', 'Ẩn sản phẩm thành công');

        return Redirect::to('show-product');
    }

    public function edit_product($product_id)
    {
        $this->AuthCheck();
        $cate_product = DB::table('tbl_category_product')
            ->orderBy('category_id', 'asc')
            ->get();
        $brand_product = DB::table('tbl_brand')
            ->orderBy('brand_id', 'asc')
            ->get();

        $edit_product = DB::table('tbl_product')
            ->where('product_id', $product_id)
            ->get();

        $manager_product = view('admin.product.edit_product')
            ->with('edit_product', $edit_product)
            ->with('cate_product', $cate_product)
            ->with('brand_product', $brand_product);

        return view('admin_layout')->with('admin.product.edit_product', $manager_product);
    }

    public function update_product(Request $request, $product_id)
    {
        $this->AuthCheck();
        $data = array();
        $data['product_name'] = $request->product_name;
        $data['product_price'] = $request->product_price;
        $data['product_color'] = $request->product_color;
        $data['product_desc'] = $request->product_desc;
        $data['product_content'] = $request->product_content;
        $data['category_id'] = $request->product_cate;
        $data['brand_id'] = $request->product_brand;
        $get_image = $request->file('product_image');

        if ($get_image) {
            $get_name_image = $get_image->getClientOriginalName();
            $name_image = current(explode('.', $get_name_image));
            $new_image = $name_image . time() . '.' . $get_image->getClientOriginalExtension();
            $get_image->move('public/uploads/product', $new_image);
            $data['product_image'] = $new_image;
            DB::table('tbl_product')->where('product_id', $product_id)->update($data);
            Session::put('message', 'Cập nhật sản phẩm thành công');

            return Redirect::to('show-product');
        }
        DB::table('tbl_product')
            ->where('product_id', $product_id)
            ->update($data);
        Session::put('message', 'Cập nhật sản phẩm thành công');

        return Redirect::to('show-product');
    }

    public function delete_product($product_id)
    {
        $this->AuthCheck();
        DB::table('tbl_product')
            ->where('product_id', $product_id)
            ->delete();
        Session::put('message', 'Xoá sản phẩm thành công');

        return Redirect::to('show-product');
    }


    //Xử lý giao diện
    public function detail_product($product_id)
    {
        //Danh mục sản phẩm
        $cate_post = CategoryPost::orderBy('cate_post_id', 'asc')->get();
        $cate_product = DB::table('tbl_category_product')
            ->where('category_status', '1')
            ->orderBy('category_id', 'asc')
            ->get();
        $brand_product = DB::table('tbl_brand')
            ->where('brand_status', '1')
            ->orderBy('brand_id', 'asc')
            ->get();

        $details_product = DB::table('tbl_product')
            ->join('tbl_category_product', 'tbl_category_product.category_id', '=', 'tbl_product.category_id')
            ->join('tbl_brand', 'tbl_brand.brand_id', '=', 'tbl_product.brand_id')
            ->where('tbl_product.product_id', $product_id)
            ->get();

        foreach ($details_product as $key => $value)
            $category_id = $value->category_id;

        $related_product = DB::table('tbl_product')
            ->join('tbl_category_product', 'tbl_category_product.category_id', '=', 'tbl_product.category_id')
            ->join('tbl_brand', 'tbl_brand.brand_id', '=', 'tbl_product.brand_id')
            ->where('tbl_category_product.category_id', $category_id)
            ->whereNotIn('tbl_product.product_id', [$product_id])
            ->get();

        return view('pages.sanpham.show_detail')
            ->with('category', $cate_product)
            ->with('brand', $brand_product)
            ->with('details', $details_product)
            ->with('relate', $related_product)
            ->with('cate_post', $cate_post);
    }
}
