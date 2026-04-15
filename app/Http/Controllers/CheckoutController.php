<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\Enrollment;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class CheckoutController extends Controller
{
    public function index(Request $request): Response|RedirectResponse
    {
        $ids = (array) $request->session()->get('cart', []);
        if (empty($ids)) {
            return redirect()->route('cart.index')->with('error', __('السلة فارغة.'));
        }

        $courses = Course::query()
            ->whereIn('id', $ids)
            ->with(['category:id,name', 'instructor:id,name'])
            ->get()
            ->map(fn (Course $c) => [
                'id' => $c->id,
                'title' => $c->title,
                'thumbnail' => $c->thumbnail,
                'price' => (float) $c->price,
            ])
            ->values()
            ->all();

        $subtotal = collect($courses)->sum('price');

        return Inertia::render('Checkout', [
            'courses' => $courses,
            'subtotal' => round($subtotal, 2),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'card_number' => ['required', 'string', 'min:13', 'max:19'],
            'card_exp_month' => ['required', 'string', 'size:2'],
            'card_exp_year' => ['required', 'string', 'size:4'],
            'card_cvv' => ['required', 'string', 'min:3', 'max:4'],
            'card_name' => ['required', 'string', 'max:255'],
        ]);

        $ids = (array) $request->session()->get('cart', []);
        if (empty($ids)) {
            return redirect()->route('cart.index')->with('error', __('السلة فارغة.'));
        }

        $user = $request->user();
        $now = now();

        foreach ($ids as $courseId) {
            $courseId = (int) $courseId;
            Enrollment::firstOrCreate(
                [
                    'user_id' => $user->id,
                    'course_id' => $courseId,
                ],
                [
                    'progress' => 0,
                    'status' => 'active',
                    'enrolled_at' => $now,
                ]
            );
        }

        $request->session()->forget('cart');

        return redirect()->route('checkout.success')->with('success', __('تم الدفع بنجاح. تم تسجيلك في الدورات.'));
    }

    public function success(Request $request): Response
    {
        return Inertia::render('CheckoutSuccess', [
            'message' => $request->session()->get('success'),
        ]);
    }
}
