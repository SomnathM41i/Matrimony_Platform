<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

// ──────────────────────────────────────────────────────────────────────────────
// SEO SETTINGS MODEL
// ──────────────────────────────────────────────────────────────────────────────

class SeoSetting extends Model
{
    protected $fillable = [
        'page_identifier', 'page_type',
        'meta_title', 'meta_description', 'meta_keywords',
        'og_title', 'og_description', 'og_image',
        'canonical_url', 'robots',
        'profile_title_template', 'profile_description_template',
    ];

    /**
     * Get SEO data for a given page identifier.
     * Cached for performance.
     */
    public static function forPage(string $identifier): ?self
    {
        return \Cache::rememberForever("seo:{$identifier}", function () use ($identifier) {
            return static::where('page_identifier', $identifier)->first();
        });
    }

    /**
     * Generate profile SEO using templates.
     * e.g. template: "{{name}} - {{age}} year old {{religion}} from {{city}}"
     */
    public function renderProfileTitle(array $data): string
    {
        return $this->fillTemplate($this->profile_title_template ?? '', $data);
    }

    public function renderProfileDescription(array $data): string
    {
        return $this->fillTemplate($this->profile_description_template ?? '', $data);
    }

    private function fillTemplate(string $template, array $data): string
    {
        foreach ($data as $key => $value) {
            $template = str_replace("{{$key}}", $value, $template);
        }
        return $template;
    }

    protected static function booted(): void
    {
        static::saved(fn($m)   => \Cache::forget("seo:{$m->page_identifier}"));
        static::deleted(fn($m) => \Cache::forget("seo:{$m->page_identifier}"));
    }
}