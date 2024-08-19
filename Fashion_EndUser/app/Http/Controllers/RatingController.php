<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use FashionCore\Models\Rating;
use FashionCore\Models\Product;
use Illuminate\Support\Facades\Auth;


class RatingController extends Controller
{
    public function __construct(){
        $this->middleware('filter.profanity')->only('store');
    }

    public function store(Request $request)
    {
        $request->validate([
            'order_id' => 'required|exists:orders,id',
            'rating.*' => 'required|integer|between:1,5',
            'comment.*' => 'nullable|string|max:250',
            'product_id.*' => 'required|exists:products,id',
        ],[
            'rating.*.required' => 'Vui lòng đánh giá sản phẩm.',
        ]);
            
        $orderId = $request->input('order_id');
        $productRatings = $request->input('rating'); 
        $productComments = $request->input('comment'); 
        $productIds = $request->input('product_id');
    
        try {
            foreach ($productIds as $productId) {
                $rating = $productRatings[$productId] ?? null;
                $comment = $productComments[$productId] ?? null;
    
                if ($rating === null) {
                    continue;
                }
    
                $existingReview = Rating::where('order_id', $orderId)
                                        ->where('product_id', $productId)
                                        ->where('user_id', Auth::id())
                                        ->first();
    
                $data = [
                    'order_id' => $orderId,
                    'user_id' => Auth::id(),
                    'product_id' => $productId,
                    'rating' => $rating,
                    'comment' => $comment,
                    'date' => now(), // Sử dụng thời gian hiện tại
                ];
    
                if ($existingReview) {
                    // Cập nhật đánh giá hiện tại
                    $existingReview->update($data);
                } else {
                    // Tạo đánh giá mới
                    Rating::create($data);
                }
            }
    
            return redirect()->back();
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()]);
        }
    }
    

    
}
