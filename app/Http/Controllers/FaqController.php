<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Faq;

class FaqController extends Controller
{
    public function index(Request $request) {
        // 検索ボックスに入力されたキーワードを取得する
        $keyword = $request->input('keyword');

        // キーワードが存在すれば検索を行い、そうでなければ全件取得する
        if ($keyword) {
            $faqs = Faq::where('question', 'like', "%{$keyword}%")->paginate(5);
        } else {
            $faqs = Faq::paginate(5);
        }

        return view('faqs.index', compact('keyword', 'faqs'));
    }
}
