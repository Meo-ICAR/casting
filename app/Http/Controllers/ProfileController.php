<?php

namespace App\Http\Controllers;

use App\Models\Profile;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Spatie\MediaLibrary\MediaCollections\Exceptions\FileDoesNotExist;
use Spatie\MediaLibrary\MediaCollections\Exceptions\FileIsTooBig;

class ProfileController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $profiles = Profile::with(['user', 'media'])
            ->latest()
            ->paginate(10);

        return view('profiles.index', compact('profiles'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $users = User::whereDoesntHave('profile')->pluck('name', 'id');
        return view('profiles.create', compact('users'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $this->validateProfile($request);

        try {
            $profile = Profile::create($validated);
            $this->handleMediaUploads($request, $profile);

            return redirect()->route('profiles.show', $profile)
                ->with('success', 'Profilo creato con successo!');

        } catch (\Exception $e) {
            return back()->withInput()
                ->with('error', 'Errore durante la creazione del profilo: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Profile $profile)
    {
        $profile->load(['user', 'media']);
        return view('profiles.show', compact('profile'));

    }

   public function casting(Profile $profile)
    {
            // Carichiamo i media per evitare query N+1 nella vista
    $profile->load('media');

    return view('casting.profile.show', compact('profile'));
     }
    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Profile $profile)
    {
        $users = User::pluck('name', 'id');
        return view('profiles.edit', compact('profile', 'users'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Profile $profile)
    {
        $validated = $this->validateProfile($request, $profile->id);

        try {
            $profile->update($validated);
            $this->handleMediaUploads($request, $profile);

            return redirect()->route('profiles.show', $profile)
                ->with('success', 'Profilo aggiornato con successo!');

        } catch (\Exception $e) {
            return back()->withInput()
                ->with('error', 'Errore durante l\'aggiornamento del profilo: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Profile $profile)
    {
        try {
            $profile->delete();
            return redirect()->route('profiles.index')
                ->with('success', 'Profilo eliminato con successo!');
        } catch (\Exception $e) {
            return back()->with('error', 'Errore durante l\'eliminazione del profilo: ' . $e->getMessage());
        }
    }

    /**
     * Validate the profile request.
     */
    protected function validateProfile(Request $request, $profileId = null)
    {
        $rules = [
            'user_id' => ['required', 'exists:users,id', 'unique:profiles,user_id,' . $profileId],
            'stage_name' => ['nullable', 'string', 'max:255'],
            'birth_date' => ['required', 'date'],
            'gender' => ['required', 'in:male,female,non_binary'],
            'city' => ['required', 'string', 'max:100'],
            'province' => ['nullable', 'string', 'size:2'],
            'country' => ['required', 'string', 'size:2'],
            'height_cm' => ['required', 'integer', 'min:50', 'max:250'],
            'weight_kg' => ['nullable', 'numeric', 'min:20', 'max:300'],
            'appearance' => ['nullable', 'array'],
            'appearance.eyes' => ['nullable', 'string'],
            'appearance.hair_color' => ['nullable', 'string'],
            'appearance.skin' => ['nullable', 'string'],
            'appearance.ethnicity' => ['nullable', 'string'],
            'appearance.has_tattoos' => ['boolean'],
            'capabilities' => ['nullable', 'array'],
            'capabilities.languages' => ['nullable', 'array'],
            'capabilities.skills' => ['nullable', 'array'],
            'capabilities.driving_license' => ['nullable', 'array'],
            'measurements' => ['nullable', 'array'],
            'socials' => ['nullable', 'array'],
            'is_visible' => ['boolean'],
            'is_represented' => ['boolean'],
            'agency_name' => ['nullable', 'string', 'max:255'],
        ];

        return $request->validate($rules);
    }

    /**
     * Handle file uploads for the profile.
     */
    protected function handleMediaUploads(Request $request, Profile $profile)
    {
        try {
            // Handle headshots (photos)
            if ($request->hasFile('headshots')) {
                foreach ($request->file('headshots') as $file) {
                    $profile->addMedia($file)
                        ->toMediaCollection('headshots');
                }
            }

            // Handle showreels (videos)
            if ($request->hasFile('showreels')) {
                foreach ($request->file('showreels') as $file) {
                    $profile->addMedia($file)
                        ->toMediaCollection('showreels');
                }
            }

            // Handle media deletions if needed
            if ($request->has('deleted_media')) {
                foreach ($request->deleted_media as $mediaId) {
                    $profile->media()->find($mediaId)?->delete();
                }
            }

        } catch (FileDoesNotExist | FileIsTooBig $e) {
            throw new \Exception('Errore nel caricamento del file: ' . $e->getMessage());
        }
    }

    /**
     * Toggle profile visibility.
     */
    public function toggleVisibility(Profile $profile)
    {
        $profile->update(['is_visible' => !$profile->is_visible]);
        return back()->with('success', 'Visibilità profilo aggiornata!');
    }

    /**
     * Search profiles based on various criteria.
     */
    public function search(Request $request)
    {
        $query = Profile::query();

        // Basic search on name/stage name
        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('stage_name', 'like', "%{$search}%")
                  ->orWhereHas('user', function($q) use ($search) {
                      $q->where('name', 'like', "%{$search}%");
                  });
            });
        }

        // Filter by gender
        if ($request->has('gender')) {
            $query->where('gender', $request->gender);
        }

        // Filter by age range
        if ($request->has('min_age')) {
            $minDate = now()->subYears($request->min_age)->format('Y-m-d');
            $query->where('birth_date', '<=', $minDate);
        }
        if ($request->has('max_age')) {
            $maxDate = now()->subYears($request->max_age + 1)->format('Y-m-d');
            $query->where('birth_date', '>=', $maxDate);
        }

        // Filter by height range
        if ($request->has('min_height')) {
            $query->where('height_cm', '>=', $request->min_height);
        }
        if ($request->has('max_height')) {
            $query->where('height_cm', '<=', $request->max_height);
        }
        if ($request->has('eye_color')) {
        // Cerca dove traits->eyes è uguale al valore richiesto
        $query->whereJsonContains('traits->eyes', $request->input('eye_color'));
    }

    // Filtro per skill (array nel JSON)
    if ($request->has('skill')) {
        $query->whereJsonContains('traits->skills', $request->input('skill'));
    }

        $profiles = $query->with(['user', 'media'])->paginate(12);

        return view('profiles.search', compact('profiles'));
    }
}
