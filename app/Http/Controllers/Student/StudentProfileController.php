<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;
use Inertia\Response;
use Symfony\Component\HttpFoundation\StreamedResponse;

class StudentProfileController extends Controller
{
    public function index(Request $request): Response
    {
        $user = $request->user();

        $programTitle = 'دبلوم التقنية والذكاء الاصطناعي';

        $cvUrl = $user->cv_path
            ? route('student.profile.cv.download')
            : null;

        return Inertia::render('Student/Profile', [
            'profile' => [
                'name' => $user->name,
                'email' => $user->email,
                'phone' => $user->phone,
                'national_id' => null,
                'program_title' => $programTitle,
                'avatar_url' => $user->avatar,
                'cv_url' => $cvUrl,
            ],
        ]);
    }

    public function uploadCv(Request $request): RedirectResponse
    {
        $request->validate([
            'cv' => ['required', 'file', 'mimes:pdf,doc,docx', 'max:10240'],
        ]);

        $user = $request->user();
        $disk = config('filesystems.default', 'local');

        if ($user->cv_path && Storage::disk($disk)->exists($user->cv_path)) {
            Storage::disk($disk)->delete($user->cv_path);
        }

        $filename = now()->format('Ymd_His').'_cv.'.$request->file('cv')->getClientOriginalExtension();
        $path = $request->file('cv')->storeAs(
            'student-cv/'.$user->id,
            $filename,
            $disk
        );

        $user->update(['cv_path' => $path]);

        return back()->with('success', 'تم رفع السيرة الذاتية بنجاح.');
    }

    public function downloadCv(Request $request): StreamedResponse|RedirectResponse
    {
        $user = $request->user();
        if (! $user->cv_path) {
            return back()->with('error', 'لا يوجد ملف سيرة ذاتية مرفوع.');
        }

        $disk = config('filesystems.default', 'local');
        if (! Storage::disk($disk)->exists($user->cv_path)) {
            $user->update(['cv_path' => null]);

            return back()->with('error', 'الملف غير موجود.');
        }

        $extension = pathinfo($user->cv_path, PATHINFO_EXTENSION);
        $downloadName = 'cv_'.$user->id.'.'.$extension;

        return Storage::disk($disk)->download($user->cv_path, $downloadName);
    }
}
