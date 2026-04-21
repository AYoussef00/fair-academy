<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\DigitalBook;
use App\Models\Enrollment;
use App\Models\Order;
use App\Models\Payment;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Inertia\Inertia;
use Inertia\Response;
use Symfony\Component\HttpFoundation\Response as SymfonyResponse;

class CheckoutController extends Controller
{
    public function index(Request $request): Response|RedirectResponse
    {
        $checkout = $this->buildCheckoutPayload($request);

        if (empty($checkout['items'])) {
            return redirect()->route('cart.index')->with('error', __('السلة فارغة.'));
        }

        return Inertia::render('Checkout', [
            'items' => $checkout['items'],
            'subtotal' => $checkout['subtotal'],
        ]);
    }

    public function redirectToNoon(Request $request): RedirectResponse
    {
        $checkout = $this->buildCheckoutPayload($request);

        if (empty($checkout['items'])) {
            return redirect()->route('cart.index')->with('error', __('السلة فارغة.'));
        }

        return $this->startNoonCheckout(
            $request,
            $checkout,
            route('home'),
            'تم الدفع بنجاح، وتم إنشاء الفاتورة وتسجيلها في حسابك.',
            route('cart.index')
        );
    }

    public function redirectCourseToNoon(Request $request, Course $course): RedirectResponse
    {
        abort_if($course->status !== 'published', 404);

        if ($course->enrollments()->where('user_id', $request->user()->id)->exists()) {
            return redirect()
                ->route('course.show', $course->id)
                ->with('success', 'أنت مشترك بالفعل في هذه الدورة ويمكنك البدء فورًا.');
        }

        if ((float) $course->price <= 0) {
            Enrollment::firstOrCreate(
                [
                    'user_id' => $request->user()->id,
                    'course_id' => $course->id,
                ],
                [
                    'progress' => 0,
                    'status' => 'active',
                    'enrolled_at' => now(),
                ]
            );

            return redirect()
                ->route('course.show', $course->id)
                ->with('success', 'تم تفعيل الدورة المجانية بنجاح، ويمكنك البدء الآن.');
        }

        $checkout = [
            'course_ids' => [$course->id],
            'book_ids' => [],
            'items' => [[
                'key' => 'course-'.$course->id,
                'id' => $course->id,
                'type' => 'course',
                'title' => $course->title,
                'price' => (float) $course->price,
            ]],
            'subtotal' => round((float) $course->price, 2),
            'description' => $course->title,
        ];

        return $this->startNoonCheckout(
            $request,
            $checkout,
            route('course.show', $course->id),
            'تم الدفع بنجاح، وتم تفعيل الدورة وفتح محتواها داخل حسابك.',
            route('course.show', $course->id)
        );
    }

    private function startNoonCheckout(
        Request $request,
        array $checkout,
        string $successRedirect,
        string $successMessage,
        ?string $errorRedirect = null
    ): RedirectResponse {
        $redirectTarget = $errorRedirect ?: route('cart.index');

        if (! $this->hasNoonCredentials()) {
            return redirect()->to($redirectTarget)->with('error', __('بيانات ربط Noon غير مكتملة في إعدادات المشروع.'));
        }

        $reference = 'EA-'.now()->format('YmdHis').'-'.$request->user()->id.'-'.random_int(1000, 9999);
        $returnUrl = (string) config('services.noon.success_url');

        $payload = [
            'apiOperation' => 'INITIATE',
            'order' => [
                'reference' => $reference,
                'amount' => (float) number_format($checkout['subtotal'], 2, '.', ''),
                'currency' => 'SAR',
                'name' => 'طلب جديد من منصة eacademy',
                'category' => 'pay',
            ],
            'configuration' => [
                'returnUrl' => $returnUrl,
                'paymentAction' => 'SALE',
            ],
        ];

        $response = Http::withHeaders($this->noonHeaders())
            ->acceptJson()
            ->post($this->noonBaseUrl().'/payment/v1/order', $payload);

        if (! $response->successful()) {
            return redirect()->to($redirectTarget)->with('error', $this->extractNoonError($response));
        }

        $data = $response->json();
        $checkoutUrl = data_get($data, 'result.checkoutData.postUrl');
        $orderId = data_get($data, 'result.order.id') ?? data_get($data, 'result.orderId');

        if ((int) data_get($data, 'resultCode', -1) !== 0 || ! $checkoutUrl) {
            return redirect()->to($redirectTarget)->with('error', data_get($data, 'message', __('تعذر إنشاء طلب الدفع عبر Noon.')));
        }

        $request->session()->put('pending_checkout', [
            'reference' => $reference,
            'order_id' => $orderId,
            'course_ids' => $checkout['course_ids'],
            'book_ids' => $checkout['book_ids'],
            'items' => $checkout['items'],
            'subtotal' => $checkout['subtotal'],
            'description' => $checkout['description'],
            'success_redirect' => $successRedirect,
            'success_message' => $successMessage,
        ]);

        return redirect()->away($checkoutUrl);
    }

    public function store(Request $request): RedirectResponse|SymfonyResponse
    {
        return $this->redirectToNoon($request);
    }

    public function success(Request $request): Response|RedirectResponse
    {
        $pendingCheckout = (array) $request->session()->get('pending_checkout', []);
        $processedReferences = (array) $request->session()->get('processed_checkout_references', []);
        $successRedirect = (string) ($pendingCheckout['success_redirect'] ?? route('home'));
        $successMessage = (string) ($pendingCheckout['success_message'] ?? 'تم الدفع بنجاح، وتم إنشاء الفاتورة وتسجيلها في حسابك.');

        if (empty($pendingCheckout)) {
            return redirect()->route('home')->with('success', $request->session()->get('success') ?? 'تمت معالجة الدفع بنجاح.');
        }

        $reference = (string) ($pendingCheckout['reference'] ?? '');

        if ($reference !== '' && in_array($reference, $processedReferences, true)) {
            return redirect()->to($successRedirect)->with('success', $successMessage);
        }

        $orderId = (string) ($request->query('orderId') ?: ($pendingCheckout['order_id'] ?? ''));
        if ($orderId === '') {
            return Inertia::render('CheckoutFailed', [
                'message' => 'تعذر التحقق من عملية الدفع من Noon.',
            ]);
        }

        if (! $this->hasNoonCredentials()) {
            return Inertia::render('CheckoutFailed', [
                'message' => 'بيانات ربط Noon غير مكتملة في إعدادات المشروع.',
            ]);
        }

        $response = Http::withHeaders($this->noonHeaders())
            ->acceptJson()
            ->get($this->noonBaseUrl().'/payment/v1/order/'.$orderId);

        if (! $response->successful() || ! $this->isNoonSaleSuccess($response->json())) {
            return Inertia::render('CheckoutFailed', [
                'message' => 'لم يتم تأكيد عملية الدفع من Noon. يمكنك المحاولة مرة أخرى.',
            ]);
        }

        $courseIds = array_values(array_unique((array) ($pendingCheckout['course_ids'] ?? [])));
        $bookIds = array_values(array_unique((array) ($pendingCheckout['book_ids'] ?? [])));
        $items = collect((array) ($pendingCheckout['items'] ?? []))
            ->map(fn (array $item) => [
                'type' => (string) ($item['type'] ?? ''),
                'id' => isset($item['id']) ? (int) $item['id'] : null,
                'title' => (string) ($item['title'] ?? ''),
                'price' => (float) ($item['price'] ?? 0),
            ])
            ->filter(fn (array $item) => $item['type'] !== '' && $item['title'] !== '')
            ->values();
        $description = (string) ($pendingCheckout['description'] ?? 'مشتريات من منصة eacademy');
        $user = $request->user();
        $now = now();

        DB::transaction(function () use ($courseIds, $bookIds, $items, $user, $now, $description, $pendingCheckout, $orderId): void {
            $order = Order::firstOrCreate(
                ['reference' => (string) $pendingCheckout['reference']],
                [
                    'user_id' => $user->id,
                    'description' => $description,
                    'total' => (float) ($pendingCheckout['subtotal'] ?? 0),
                    'status' => 'paid',
                ]
            );

            if ($order->user_id !== $user->id || $order->status !== 'paid') {
                $order->forceFill([
                    'user_id' => $user->id,
                    'description' => $description,
                    'total' => (float) ($pendingCheckout['subtotal'] ?? 0),
                    'status' => 'paid',
                ])->save();
            }

            $order->items()->delete();

            if ($items->isNotEmpty()) {
                $order->items()->createMany(
                    $items->map(fn (array $item) => [
                        'item_type' => $item['type'],
                        'item_id' => $item['id'],
                        'title' => $item['title'],
                        'price' => $item['price'],
                        'quantity' => 1,
                    ])->all()
                );
            }

            Payment::updateOrCreate(
                ['transaction_id' => $orderId],
                [
                    'order_id' => $order->id,
                    'payment_method' => 'Noon',
                    'amount' => (float) ($pendingCheckout['subtotal'] ?? 0),
                    'status' => 'paid',
                    'paid_at' => $now,
                ]
            );

            foreach ($courseIds as $courseId) {
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

            if (! empty($bookIds)) {
                DigitalBook::query()
                    ->whereIn('id', $bookIds)
                    ->where('status', 'approved')
                    ->increment('purchase_count');
            }
        });

        $processedReferences[] = $reference;
        $request->session()->put('processed_checkout_references', array_values(array_unique($processedReferences)));
        $request->session()->forget(['cart', 'book_cart', 'pending_checkout']);

        return redirect()->to($successRedirect)->with('success', $successMessage);
    }

    public function failed(Request $request): Response
    {
        $request->session()->forget('pending_checkout');

        return Inertia::render('CheckoutFailed', [
            'message' => 'تم إلغاء عملية الدفع أو لم تكتمل على Noon.',
        ]);
    }

    private function buildCheckoutPayload(Request $request): array
    {
        $courseIds = array_values(array_unique((array) $request->session()->get('cart', [])));
        $bookIds = array_values(array_unique((array) $request->session()->get('book_cart', [])));

        $courseItems = Course::query()
            ->whereIn('id', $courseIds)
            ->with(['category:id,name', 'instructor:id,name'])
            ->get()
            ->map(fn (Course $c) => [
                'key' => 'course-'.$c->id,
                'id' => $c->id,
                'type' => 'course',
                'title' => $c->title,
                'price' => (float) $c->price,
            ])
            ->values();

        $bookItems = DigitalBook::query()
            ->whereIn('id', $bookIds)
            ->where('status', 'approved')
            ->get()
            ->map(fn (DigitalBook $book) => [
                'key' => 'book-'.$book->id,
                'id' => $book->id,
                'type' => 'book',
                'title' => $book->title,
                'price' => (float) $book->price,
            ])
            ->values();

        $items = $courseItems
            ->concat($bookItems)
            ->values()
            ->all();

        return [
            'course_ids' => $courseItems->pluck('id')->all(),
            'book_ids' => $bookItems->pluck('id')->all(),
            'items' => $items,
            'subtotal' => round(collect($items)->sum('price'), 2),
            'description' => collect($items)->pluck('title')->take(3)->implode(' - '),
        ];
    }

    private function noonBaseUrl(): string
    {
        return rtrim((string) config('services.noon.api_url'), '/');
    }

    private function noonHeaders(): array
    {
        $mode = str_contains($this->noonBaseUrl(), 'test') ? 'Test' : 'Live';
        $businessId = (string) config('services.noon.business_id');
        $applicationId = (string) config('services.noon.application_id');
        $apiKey = (string) config('services.noon.api_key');

        return [
            'Content-Type' => 'application/json',
            'Authorization' => 'Key_'.$mode.' '.base64_encode($businessId.'.'.$applicationId.':'.$apiKey),
        ];
    }

    private function hasNoonCredentials(): bool
    {
        return $this->noonBaseUrl() !== ''
            && filled(config('services.noon.business_id'))
            && filled(config('services.noon.application_id'))
            && filled(config('services.noon.api_key'));
    }

    private function isNoonSaleSuccess(array $response): bool
    {
        $transaction = data_get($response, 'result.transactions.0', []);

        return data_get($transaction, 'type') === 'SALE'
            && data_get($transaction, 'status') === 'SUCCESS';
    }

    private function extractNoonError(\Illuminate\Http\Client\Response $response): string
    {
        $data = $response->json();
        $message = data_get($data, 'message')
            ?? data_get($data, 'result.message')
            ?? 'تعذر الاتصال ببوابة Noon حالياً.';

        $activityId = data_get($data, 'activityId') ?? data_get($data, 'requestReference');

        if ($activityId) {
            return $message.' '.'('.'Ref: '.$activityId.')';
        }

        return $message.' '.'('.'HTTP '.$response->status().')';
    }
}
