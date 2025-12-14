<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Project;
use App\Models\Profile;
use App\Models\Role;
use App\Models\Application;
use Illuminate\Database\Seeder;
use Carbon\Carbon;

class CinemaCastingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get all users
        $admin = User::where('email', 'admin@example.com')->first();
        $director = User::where('email', 'director@example.com')->first();
        $actors = User::where('role', 'actor')->get();

        // Create sample projects
        $projects = [
            [
                'title' => 'Il Segreto del Bosco',
                'type' => 'feature_film',
                'status' => 'casting',
                'description' => 'Un thriller ambientato in un piccolo paese di montagna dove scompaiono misteriosamente alcuni abitanti.',
                'production_company' => 'Cinema Italiano SRL',
                'start_date' => Carbon::now()->addMonths(2),
            ],
            [
                'title' => 'L\'Ultimo Ballo',
                'type' => 'tv_series',
                'status' => 'production',
                'description' => 'Una serie drammatica che segue le vite di una scuola di danza classica.',
                'production_company' => 'Rai Fiction',
                'start_date' => Carbon::now()->addMonth(),
            ],
            [
                'title' => 'Spot Pubblicitario - Auto Elettriche',
                'type' => 'commercial',
                'status' => 'casting',
                'description' => 'Spot per una nuova linea di auto elettriche di lusso.',
                'production_company' => 'AdvertiseMe',
                'start_date' => Carbon::now()->addWeeks(2),
            ]
        ];

        foreach ($projects as $projectData) {
            $project = Project::create(array_merge($projectData, [
                'user_id' => $director->id
            ]));

            // Create sample roles for each project
            $this->createRolesForProject($project);
        }

        // Create profiles for actors and make them apply for roles
        $this->createActorProfiles($actors);
    }

    /**
     * Create sample roles for a project
     */
    private function createRolesForProject(Project $project)
    {
        $roles = [
            [
                'name' => 'Protagonista',
                'description' => 'Il personaggio principale della storia',
                'requirements' => [
                    'age_range' => [25, 40],
                    'gender' => ['male', 'female'],
                    'skills' => ['recitazione teatrale', 'danza']
                ],
                'salary_min' => 5000,
                'salary_max' => 10000,
                'is_open' => true
            ],
            [
                'name' => 'Antagonista',
                'description' => 'L\'antagonista della storia',
                'requirements' => [
                    'age_range' => [30, 50],
                    'gender' => ['male'],
                    'skills' => ['recitazione drammatica']
                ],
                'salary_min' => 4000,
                'salary_max' => 8000,
                'is_open' => true
            ],
            [
                'name' => 'Comprimario',
                'description' => 'Ruolo di supporto con diverse scene',
                'requirements' => [
                    'age_range' => [20, 60],
                    'gender' => ['male', 'female'],
                    'skills' => ['recitazione']
                ],
                'salary_min' => 2000,
                'salary_max' => 4000,
                'is_open' => true
            ]
        ];

        foreach ($roles as $roleData) {
            Role::create(array_merge($roleData, [
                'project_id' => $project->id,
                'requirements' => json_encode($roleData['requirements'])
            ]));
        }
    }

    /**
     * Create profiles for actors and make them apply for roles
     */
    private function createActorProfiles($actors)
    {
        $genders = ['male', 'female'];
        $cities = ['Roma', 'Milano', 'Torino', 'Napoli', 'Firenze', 'Bologna', 'Venezia'];
        $eyeColors = ['blue', 'green', 'brown', 'black', 'hazel'];
        $hairColors = ['blonde', 'brown', 'black', 'red', 'grey'];
        $skinTones = ['fair', 'medium', 'olive', 'dark'];
        $ethnicities = ['caucasian', 'mediterranean', 'african', 'asian', 'hispanic'];
        $languages = [
            'Italiano', 'Inglese', 'Francese', 'Spagnolo', 'Tedesco',
            'Russo', 'Cinese', 'Giapponese', 'Arabo', 'Portoghese'
        ];
        $skills = [
            'recitazione teatrale', 'danza classica', 'arti marziali', 'equitazione',
            'nuoto', 'canto lirico', 'acrobazie', 'guida moto', 'lingue straniere',
            'recitazione cinematografica', 'improvvisazione', 'combattimento scenico'
        ];

        foreach ($actors as $actor) {
            $gender = $genders[array_rand($genders)];
            $birthDate = Carbon::now()->subYears(rand(18, 60))->subMonths(rand(0, 11))->subDays(rand(0, 30));
            $age = $birthDate->diffInYears(Carbon::now());

            $profile = Profile::create([
                'user_id' => $actor->id,
                'stage_name' => $actor->name === 'Test User' ? null : $actor->name . ' ' . $actor->last_name,
                'slug' => \Illuminate\Support\Str::slug($actor->name . '-' . $actor->id),
                'birth_date' => $birthDate->format('Y-m-d'),
                'gender' => $gender,
                'city' => $cities[array_rand($cities)],
                'province' => strtoupper(substr(str_shuffle('ABCDEFGHIJKLMNOPQRSTUVWXYZ'), 0, 2)),
                'country' => 'IT',
                'height_cm' => $gender === 'male' ? rand(165, 195) : rand(155, 185),
                'weight_kg' => $gender === 'male' ? rand(60, 90) : rand(45, 75),
                'appearance' => [
                    'eyes' => $eyeColors[array_rand($eyeColors)],
                    'hair_color' => $hairColors[array_rand($hairColors)],
                    'skin' => $skinTones[array_rand($skinTones)],
                    'ethnicity' => $ethnicities[array_rand($ethnicities)],
                    'has_tattoos' => rand(0, 1),
                    'body_type' => ['slim', 'athletic', 'average', 'muscular', 'curvy'][array_rand([0, 1, 2, 3, 4])],
                ],
                'measurements' => [
                    'shoes' => $gender === 'male' ? rand(40, 46) : rand(36, 42),
                    'jacket' => $gender === 'male' ? rand(46, 56) : rand(36, 46),
                    'chest' => $gender === 'male' ? rand(90, 120) : rand(80, 110),
                    'waist' => $gender === 'male' ? rand(75, 100) : rand(60, 90),
                    'hips' => $gender === 'male' ? rand(90, 110) : rand(85, 120),
                ],
                'capabilities' => [
                    'languages' => array_rand(array_flip($languages), rand(1, 3)),
                    'skills' => array_rand(array_flip($skills), rand(2, 5)),
                    'driving_license' => array_rand(array_flip(['AM', 'A1', 'A', 'B', 'C']), rand(1, 2)),
                ],
                'socials' => [
                    'instagram' => 'https://instagram.com/' . strtolower(str_replace(' ', '', $actor->name)),
                    'imdb' => 'https://www.imdb.com/name/' . strtolower(str_replace(' ', '', $actor->name)) . rand(1000, 9999),
                ],
                'is_visible' => true,
                'is_represented' => rand(0, 1),
                'agency_name' => rand(0, 1) ? ['Talent Agency', 'Star Management', 'Artisti Associati', 'Agenzia 360°'][array_rand([0, 1, 2, 3])] : null,
            ]);

            // Make the actor apply for some roles
            $this->createApplications($profile);
        }
    }

    /**
     * Create applications for a profile
     */
    private function createApplications(Profile $profile)
    {
        // Get some random open roles
        $roles = Role::where('is_open', true)
            ->inRandomOrder()
            ->limit(rand(1, 5))
            ->get();

        foreach ($roles as $role) {
            // Check if the profile meets the role requirements
            $requirements = json_decode($role->requirements, true);
            $age = $profile->birth_date ? Carbon::parse($profile->birth_date)->age : 30;

            // Skip if age doesn't match
            if (isset($requirements['age_range']) &&
                ($age < $requirements['age_range'][0] || $age > $requirements['age_range'][1])) {
                continue;
            }

            // Skip if gender doesn't match
            if (isset($requirements['gender']) &&
                !in_array($profile->gender, $requirements['gender'])) {
                continue;
            }

            // Skip if skills don't match (at least one required skill must match)
            if (isset($requirements['skills']) &&
                !array_intersect($requirements['skills'], $profile->capabilities['skills'] ?? [])) {
                continue;
            }

            // Create the application
            Application::create([
                'role_id' => $role->id,
                'profile_id' => $profile->id,
                'status' => ['pending', 'under_review', 'callback', 'rejected'][array_rand([0, 1, 2, 3])],
                'cover_letter' => rand(0, 1) ? 'Sono molto interessato/a a questo ruolo e credo di essere perfetto/a per la parte. Ho esperienza in produzioni simili e sono disponibile per qualsiasi audizione.' : null,
                'director_notes' => null, // Will be filled by the director
            ]);
        }
    }
}
