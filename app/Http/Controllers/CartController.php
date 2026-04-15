<?php

namespace App\Http\Controllers;

use App\Models\Course;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class CartController extends Controller
{
    public function index(Request $request): Response
    {
        $ids = array_values(array_unique((array) $request->session()->get('cart', [])));
        $coursesCollection = Course::query()
            ->whereIn('id', $ids)
            ->with(['category:id,name', 'instructor:id,name', 'modules.lessons'])
            ->get()
            ->keyBy('id');
        $courses = [];
        $subtotal = 0.0;
        foreach ($ids as $id) {
            $c = $coursesCollection->get((int) $id);
            if ($c) {
                $price = (float) $c->price;
                $subtotal += $price;
                $totalLessons = $c->modules->sum(fn ($m) => $m->lessons->count());
                $courses[] = [
                    'id' => $c->id,
                    'title' => $c->title,
                    'thumbnail' => $c->thumbnail,
                    'price' => $price,
                    'category_name' => $c->category->name ?? '',
                    'instructor' => $c->instructor->name ?? '',
                    'duration' => $c->duration,
                    'total_lessons' => $totalLessons,
                ];
            }
        }

        return Inertia::render('Cart', [
            'courses' => $courses,
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

    public function remove(Request $request, Course $course): RedirectResponse
    {
        $cart = $request->session()->get('cart', []);
        $ids = is_array($cart) ? array_values(array_filter($cart, fn ($id) => (int) $id !== (int) $course->id)) : [];
        $request->session()->put('cart', $ids);

        return redirect()
            ->back()
            ->with('success', __('تم إزالة الدورة من السلة.'));
    }
}
