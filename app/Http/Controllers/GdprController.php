<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreGdprConsentRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\StreamedResponse;

class GdprController extends Controller
{
    /**
     * Show privacy policy page
     */
    public function showPrivacyPolicy()
    {
        return view('gdpr.privacy-policy');
    }

    /**
     * Accept privacy policy
     */
    public function acceptPrivacyPolicy(Request $request)
    {
        $request->validate([
            'privacy_policy' => ['required', 'accepted']
        ]);

        $user = $request->user();
        $user->acceptPrivacyPolicy(
            $request->ip(),
            $request->userAgent()
        );

        return redirect()->route('gdpr.terms')
            ->with('status', 'Privacy policy accepted successfully.');
    }

    /**
     * Show terms and conditions page
     */
    public function showTerms()
    {
        return view('gdpr.terms');
    }

    /**
     * Accept terms and conditions
     */
    public function acceptTerms(Request $request)
    {
        $request->validate([
            'terms' => ['required', 'accepted']
        ]);

        $user = $request->user();
        $user->acceptTerms(
            $request->ip(),
            $request->userAgent()
        );

        // If user hasn't given data processing consent, redirect to settings
        if (!$user->hasGivenDataProcessingConsent()) {
            return redirect()->route('gdpr.settings')
                ->with('status', 'Terms and conditions accepted. Please review your privacy settings.');
        }

        return redirect()->route('home')
            ->with('status', 'Terms and conditions accepted successfully.');
    }

    /**
     * Show privacy settings page
     */
    public function showSettings()
    {
        $user = auth()->user();
        return view('gdpr.settings', compact('user'));
    }

    /**
     * Update user consent preferences
     */
    public function updateConsent(StoreGdprConsentRequest $request)
    {
        $user = $request->user();
        $data = $request->validated();

        // If marketing consent is not given, ensure newsletter subscription is false
        if (!isset($data['marketing_consent'])) {
            $data['marketing_consent'] = false;
            $data['newsletter_subscription'] = false;
        } elseif (!isset($data['newsletter_subscription'])) {
            $data['newsletter_subscription'] = false;
        }

        // If data processing consent is given, update the timestamp
        if (isset($data['data_processing_consent']) && $data['data_processing_consent']) {
            $data['data_processing_consent_at'] = now();
        } else {
            $data['data_processing_consent'] = false;
            $data['data_processing_consent_at'] = null;
        }

        $user->update($data);

        return redirect()->back()
            ->with('status', 'Your privacy settings have been updated.');
    }

    /**
     * Export user data
     */
    public function exportData(Request $request)
    {
        $user = $request->user();

        // Create a JSON file with user data
        $data = [
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'created_at' => $user->created_at->toDateTimeString(),
                'updated_at' => $user->updated_at->toDateTimeString(),
                'privacy_policy_accepted_at' => $user->privacy_policy_accepted_at?->toDateTimeString(),
                'terms_accepted_at' => $user->terms_accepted_at?->toDateTimeString(),
                'marketing_consent' => (bool)$user->marketing_consent,
                'newsletter_subscription' => (bool)$user->newsletter_subscription,
                'data_processing_consent' => (bool)$user->data_processing_consent,
                'data_processing_consent_at' => $user->data_processing_consent_at?->toDateTimeString(),
            ],
            'profile' => $user->profile ? $user->profile->toArray() : null,
            // Add more related data as needed
        ];

        $filename = 'user-data-export-' . $user->id . '-' . now()->format('Y-m-d') . '.json';
        $fileContent = json_encode($data, JSON_PRETTY_PRINT);

        // Return the file as a download
        return response()->streamDownload(function () use ($fileContent) {
            echo $fileContent;
        }, $filename, [
            'Content-Type' => 'application/json',
        ]);
    }

    /**
     * Request data erasure
     */
    public function requestDataErasure(Request $request)
    {
        $user = $request->user();

        // In a real application, you might want to queue this
        $user->requestDataErasure();

        // Log the user out
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login')
            ->with('status', 'Your data erasure request has been received. All your personal data will be deleted within 30 days.');
    }

    /**
     * Delete user account
     */
    public function deleteAccount(Request $request)
    {
        $user = $request->user();

        // Anonymize user data
        $user->anonymize();

        // Log the user out
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        // In a real application, you might want to queue the actual deletion
        // and keep the user record for a grace period

        return redirect()->route('home')
            ->with('status', 'Your account has been deleted. All your personal data has been removed from our systems.');
    }
}
