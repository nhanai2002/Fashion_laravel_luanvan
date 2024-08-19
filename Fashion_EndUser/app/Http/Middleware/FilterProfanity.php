<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use Symfony\Component\HttpFoundation\Response;

class FilterProfanity
{
    public function handle(Request $request, Closure $next): Response
    {
        $badwords = Config::get('badwords.badwords');
        $comment = $request->input('comment');

        // Kiểm tra nếu 'comment' không phải là một chuỗi hoặc mảng
        if (!is_string($comment) && !is_array($comment)) {
            return redirect()->back()->withErrors(['comment' => 'Bình luận không hợp lệ.']);
        }

        // Nếu 'comment' là một chuỗi
        if (is_string($comment)) {
            foreach ($badwords as $badword) {
               if (stripos($comment, $badword) !== false) {
                    $comment = str_ireplace($badword, '***', $comment);
               }
            }
            $request->merge(['comment' => $comment]);
        }

        // Nếu 'comment' là một mảng
        if (is_array($comment)) {
            foreach ($comment as &$singleComment) {
               foreach ($badwords as $badword) {
                    if (stripos($singleComment, $badword) !== false) {
                        $singleComment = str_ireplace($badword, '***', $singleComment);
                    }
               }
            }
            $request->merge(['comment' => $comment]);
        }

        return $next($request);
    }
}