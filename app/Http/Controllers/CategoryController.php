<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use App\Models\Sale;
use App\Models\Stock;
use Illuminate\Http\Request;

class CategoryController extends Controller
{


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function __construct(Request $request)
    {
        $this->middleware('permission:category-list|category-create|category-edit|category-delete', ['only' => ['index', 'show']]);
        $this->middleware('permission:category-create', ['only' => ['create', 'store']]);
        $this->middleware('permission:category-delete', ['only' => ['destroy']]);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $categories = Category::paginate(10);
        return view('contents.category.index', compact('categories'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('contents.category.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $requestData['name'] = $request->name;
        $requestData['rate'] = $request->rate;

           $category = Category::create($requestData);
            return redirect()->route('category.index')->with('success', 'Category added successfully !');

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $category = Category::where('id',$id)->latest()->first();
        return view('contents.category.show', compact('category'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $category = Category::where('id',$id)->latest()->first();
        return view('contents.category.edit', compact('category'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $requestData['name'] = $request->name;
        $requestData['rate'] = $request->rate;
        $Check_category = Category::where('id',$id)->update($requestData);
            return redirect()->route('category.index')->with('success', 'Record updated successfully !');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $category = Category::where('id',$id)->delete();
        return redirect()->route('category.index')->with('success', 'Record deleted successfully !');
    }


    public function getBuyingRate(Request $request){

        $data['category'] = Product::where('category',$request->category)->whereDate('date',$request->date)->first();
        if($data['category'] == null){
            $data['category'] = Product::where('category',$request->category)->latest()->first();
        }
        $data['availabile_weight_recondition'] = Stock::where('category',$request->category)->sum('weight_recondition');
        $data['availabile_weight_reusable'] = Stock::where('category',$request->category)->sum('weight_reusable');
        return $data; // Returns all provinces
    }
    public function getSellingRate(Request $request){

        $data['category'] = Sale::where('category',$request->category)->whereDate('date',$request->date)->first();
        if($data['category'] == null){
            $data['category'] = Sale::where('category',$request->category)->latest()->first();
        }
        $data['availabile_weight_recondition'] = Stock::where('category',$request->category)->sum('weight_recondition');
        $data['availabile_weight_reusable'] = Stock::where('category',$request->category)->sum('weight_reusable');
        return $data; // Returns all provinces
    }
}
