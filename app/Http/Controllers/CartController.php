<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\DigitalBook;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;
use Inertia\Response;

class CartController extends Controller
{
    public function index(Request $request): Response
    {
        $courseIds = array_values(array_unique((array) $request->session()->get('cart', [])));
        $bookIds = array_values(array_unique((array) $request->session()->get('book_cart', [])));

        $coursesCollection = Course::query()
            ->whereIn('id', $courseIds)
            ->with(['category:id,name', 'instructor:id,name', 'modules.lessons'])
            ->get()
            ->keyBy('id');

        $booksCollection = DigitalBook::query()
            ->whereIn('id', $bookIds)
            ->where('status', 'approved')
            ->get()
            ->keyBy('id');

        $items = [];
        $subtotal = 0.0;

        foreach ($courseIds as $id) {
            $c = $coursesCollection->get((int) $id);
            if ($c) {
                $price = (float) $c->price;
                $subtotal += $price;
                $totalLessons = $c->modules->sum(fn ($m) => $m->lessons->count());
                $items[] = [
                    'key' => 'course-'.$c->id,
                    'id' => $c->id,
                    'type' => 'course',
                    'title' => $c->title,
                    'image' => $c->thumbnail,
                    'price' => $price,
                    'subtitle' => $c->instructor?->name ? 'بواسطة '.$c->instructor->name : 'دورة تدريبية',
                    'meta' => collect([
                        $c->duration,
                        $totalLessons > 0 ? $totalLessons.' من المحاضرات' : null,
                        'جميع المستويات',
                    ])->filter()->implode(' • '),
                    'badge' => 'دورة',
                    'detail_url' => '/course/'.$c->id,
                    'remove_url' => '/cart/remove/'.$c->id,
                ];
            }
        }

        foreach ($bookIds as $id) {
            $book = $booksCollection->get((int) $id);
            if ($book) {
                $price = (float) $book->price;
                $subtotal += $price;
                $items[] = [
                    'key' => 'book-'.$book->id,
                    'id' => $book->id,
                    'type' => 'book',
                    'title' => $book->title,
                    'image' => $book->cover_path ? Storage::url($book->cover_path) : null,
                    'price' => $price,
                    'subtitle' => 'كتاب رقمي',
                    'meta' => 'عدد مرات الشراء: '.$book->purchase_count,
                    'badge' => 'كتاب',
                    'detail_url' => '/digital-library/books/'.$book->id,
                    'remove_url' => '/cart/remove-book/'.$book->id,
                ];
            }
        }

        return Inertia::render('Cart', [
            'items' => $items,
            'subtotal' => round($subtotal, 2),
        ]);
    }

    public function add(Request $request, Course $course): RedirectResponse
    {
        $cart = $request->session()->get('cart', []);
        $ids = is_array($cart) ? $cart : [];

        if (! in_array($course->id, $ids, true)) {
            $ids[] = $course->id;
            $request->session()->put('cart', $ids);
        }

        return redirect()
            ->back()
            ->with('success', __('تمت إضافة الدورة إلى السلة.'));
    }

    public function addBook(Request $request, DigitalBook $digitalBook): RedirectResponse
    {
        abort_if($digitalBook->status !== 'approved', 404);

        $cart = $request->session()->get('book_cart', []);
        $ids = is_array($cart) ? $cart : [];

        if (! in_array($digitalBook->id, $ids, true)) {
            $ids[] = $digitalBook->id;
            $request->session()->put('book_cart', $ids);
        }

        return redirect()
            ->back()
            ->with('success', __('تمت إضافة الكتاب إلى السلة.'));
    }

    public function remove(Request $request, Course $course): RedirectResponse
    {
        $cart = $request->session()->get('cart', []);
        $ids = is_array($cart) ? array_values(array_filter($cart, fn ($id) => (int) $id !== (int) $course->id)) : [];
        $request->session()->put('cart', $ids);

        return redirect()
            ->back()
            ->with('success', __('تم إزالة الدورة من السلة.'));
    }

    public function removeBook(Request $request, DigitalBook $digitalBook): RedirectResponse
    {
        $cart = $request->session()->get('book_cart', []);
        $ids = is_array($cart) ? array_values(array_filter($cart, fn ($id) => (int) $id !== (int) $digitalBook->id)) : [];
        $request->session()->put('book_cart', $ids);

        return redirect()
            ->back()
            ->with('success', __('تم إزالة الكتاب من السلة.'));
    }
}
