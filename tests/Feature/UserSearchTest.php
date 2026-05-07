<?php

namespace Tests\Feature;

use App\Models\Caste;
use App\Models\Country;
use App\Models\ProfilePhoto;
use App\Models\Religion;
use App\Models\Role;
use App\Models\SavedSearch;
use App\Models\State;
use App\Models\User;
use App\Models\UserProfile;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Carbon;
use Tests\TestCase;

class UserSearchTest extends TestCase
{
    use RefreshDatabase;

    public function test_keyword_filters_and_pagination_return_correct_profiles(): void
    {
        [$role, $religion, $caste, $country, $state] = $this->lookups();
        $authUser = $this->user($role, 'male', 30, ['name' => 'Search Owner']);

        $expectedIds = collect(range(1, 13))->map(function (int $i) use ($role, $religion, $caste, $country, $state) {
            $user = $this->user($role, 'female', 28, [
                'name' => "Asha Match {$i}",
                'profile_slug' => "asha-match-{$i}",
                'last_login_at' => now()->subMinutes($i),
            ]);

            $this->profile($user, [
                'first_name' => 'Asha',
                'last_name' => "Match{$i}",
                'religion_id' => $religion->id,
                'caste_id' => $caste->id,
                'country_id' => $country->id,
                'state_id' => $state->id,
                'height_cm' => 164,
            ]);
            $this->photo($user);

            return $user->id;
        });

        $wrongReligion = Religion::create(['name' => 'Sikh']);
        $wrongUser = $this->user($role, 'female', 28, ['name' => 'Asha Wrong Religion']);
        $this->profile($wrongUser, ['first_name' => 'Asha', 'religion_id' => $wrongReligion->id]);
        $this->photo($wrongUser);

        $hiddenUser = $this->user($role, 'female', 28, ['name' => 'Asha Hidden']);
        $this->profile($hiddenUser, ['first_name' => 'Asha', 'religion_id' => $religion->id, 'profile_visibility' => 'hidden']);
        $this->photo($hiddenUser);

        $this->actingAs($authUser)
            ->get(route('user.search.index', [
                'keyword' => 'Asha',
                'religion_id' => $religion->id,
                'age_min' => 24,
                'age_max' => 35,
                'with_photo' => '1',
            ]))
            ->assertOk()
            ->assertViewHas('results', function ($results) use ($expectedIds, $wrongUser, $hiddenUser) {
                $pageIds = $results->getCollection()->pluck('id');

                return $results->total() === 13
                    && $results->count() === 12
                    && $pageIds->diff($expectedIds)->isEmpty()
                    && !$pageIds->contains($wrongUser->id)
                    && !$pageIds->contains($hiddenUser->id);
            });

        $this->actingAs($authUser)
            ->get(route('user.search.index', [
                'keyword' => 'Asha',
                'religion_id' => $religion->id,
                'age_min' => 24,
                'age_max' => 35,
                'with_photo' => '1',
                'page' => 2,
            ]))
            ->assertOk()
            ->assertViewHas('results', fn($results) => $results->currentPage() === 2 && $results->count() === 1);
    }

    public function test_reversed_age_and_height_ranges_are_normalised(): void
    {
        [$role] = $this->lookups();
        $authUser = $this->user($role, 'male', 30);

        $candidate = $this->user($role, 'female', 30, ['name' => 'Range Candidate']);
        $this->profile($candidate, ['first_name' => 'Range', 'height_cm' => 165]);

        $this->actingAs($authUser)
            ->get(route('user.search.index', [
                'age_min' => 35,
                'age_max' => 25,
                'height_min' => 170,
                'height_max' => 160,
            ]))
            ->assertOk()
            ->assertViewHas('results', fn($results) => $results->total() === 1 && $results->first()->is($candidate))
            ->assertViewHas('filters', fn($filters) => $filters['age_min'] === 25
                && $filters['age_max'] === 35
                && $filters['height_min'] === 160
                && $filters['height_max'] === 170);
    }

    public function test_saved_search_uses_valid_search_type(): void
    {
        [$role] = $this->lookups();
        $authUser = $this->user($role, 'male', 30);

        $this->actingAs($authUser)
            ->post(route('user.search.save'), [
                'search_name' => 'Asha keyword',
                'keyword' => 'Asha',
                'age_min' => 24,
                'age_max' => 35,
            ])
            ->assertRedirect();

        $savedSearch = SavedSearch::firstOrFail();

        $this->assertSame('keyword', $savedSearch->search_type);
        $this->assertSame('Asha', $savedSearch->keyword);
        $this->assertSame('Asha keyword', $savedSearch->name);
    }

    private function lookups(): array
    {
        $role = Role::create(['name' => 'user', 'display_name' => 'End User']);
        $religion = Religion::create(['name' => 'Hindu']);
        $caste = Caste::create(['religion_id' => $religion->id, 'name' => 'Brahmin']);
        $country = Country::create(['name' => 'India', 'iso_code' => 'IN']);
        $state = State::create(['country_id' => $country->id, 'name' => 'Maharashtra']);

        return [$role, $religion, $caste, $country, $state];
    }

    private function user(Role $role, string $gender, int $age, array $attributes = []): User
    {
        return User::factory()->create(array_merge([
            'role_id' => $role->id,
            'gender' => $gender,
            'date_of_birth' => Carbon::today()->subYears($age)->subMonth(),
            'account_status' => 'active',
            'profile_status' => 'complete',
            'email_verified_at' => now(),
        ], $attributes));
    }

    private function profile(User $user, array $attributes = []): UserProfile
    {
        return UserProfile::create(array_merge([
            'user_id' => $user->id,
            'first_name' => $user->name,
            'last_name' => 'Tester',
            'marital_status' => 'never_married',
            'height_cm' => 165,
            'profile_visibility' => 'everyone',
            'completion_percentage' => 80,
        ], $attributes));
    }

    private function photo(User $user, bool $approved = true): ProfilePhoto
    {
        return ProfilePhoto::create([
            'user_id' => $user->id,
            'path' => "profiles/{$user->id}/photo.jpg",
            'is_primary' => true,
            'is_approved' => $approved,
        ]);
    }
}
