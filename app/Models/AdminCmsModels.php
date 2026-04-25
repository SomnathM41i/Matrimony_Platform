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

// ──────────────────────────────────────────────────────────────────────────────
// SITEMAP ENTRY MODEL
// ──────────────────────────────────────────────────────────────────────────────

class SitemapEntry extends Model
{
    protected $fillable = [
        'url', 'lastmod', 'changefreq', 'priority', 'type', 'is_active',
    ];

    protected $casts = [
        'lastmod'   => 'date',
        'priority'  => 'float',
        'is_active' => 'boolean',
    ];
}

// ──────────────────────────────────────────────────────────────────────────────
// ROBOTS TXT SETTINGS MODEL (NEW)
// ──────────────────────────────────────────────────────────────────────────────

class RobotsTxtSetting extends Model
{
    protected $fillable = [
        'user_agent', 'directive', 'value', 'sort_order', 'is_active', 'comment',
    ];

    protected $casts = ['is_active' => 'boolean'];

    /**
     * Generate the full robots.txt content from the database.
     */
    public static function generate(): string
    {
        $entries = static::where('is_active', true)->orderBy('user_agent')->orderBy('sort_order')->get();

        $lines = [];
        $currentAgent = null;

        foreach ($entries as $entry) {
            if ($entry->directive === 'sitemap') {
                $lines[] = "Sitemap: {$entry->value}";
                continue;
            }

            if ($currentAgent !== $entry->user_agent) {
                if ($currentAgent !== null) $lines[] = '';
                $lines[] = "User-agent: {$entry->user_agent}";
                $currentAgent = $entry->user_agent;
            }

            $directive = match ($entry->directive) {
                'allow'       => 'Allow',
                'disallow'    => 'Disallow',
                'crawl_delay' => 'Crawl-delay',
                'host'        => 'Host',
                default       => ucfirst($entry->directive),
            };

            $lines[] = "{$directive}: {$entry->value}";
        }

        return implode("\n", $lines);
    }
}

// ──────────────────────────────────────────────────────────────────────────────
// CMS PAGE MODEL
// ──────────────────────────────────────────────────────────────────────────────

class CmsPage extends Model
{
    protected $fillable = [
        'title', 'slug', 'content', 'excerpt',
        'meta_title', 'meta_description',
        'is_active', 'is_system', 'sort_order', 'template',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'is_system' => 'boolean',   // system pages (Home, About) cannot be deleted
    ];

    public function menus()
    {
        return $this->hasMany(Menu::class);
    }

    public function getUrlAttribute(): string
    {
        return route('cms.show', $this->slug);
    }
}

// ──────────────────────────────────────────────────────────────────────────────
// MENU LOCATION MODEL
// ──────────────────────────────────────────────────────────────────────────────

class MenuLocation extends Model
{
    protected $fillable = ['key', 'label', 'description'];

    public function menus()
    {
        return $this->hasMany(Menu::class)->orderBy('sort_order');
    }

    public function topLevelMenus()
    {
        return $this->hasMany(Menu::class)->whereNull('parent_id')->orderBy('sort_order');
    }
}

// ──────────────────────────────────────────────────────────────────────────────
// MENU MODEL
// ──────────────────────────────────────────────────────────────────────────────

class Menu extends Model
{
    protected $fillable = [
        'menu_location_id', 'parent_id', 'label', 'url', 'route_name',
        'cms_page_id', 'icon', 'target', 'is_active', 'sort_order', 'visibility',
    ];

    protected $casts = ['is_active' => 'boolean'];

    public function location()
    {
        return $this->belongsTo(MenuLocation::class, 'menu_location_id');
    }

    public function parent()
    {
        return $this->belongsTo(Menu::class, 'parent_id');
    }

    public function children()
    {
        return $this->hasMany(Menu::class, 'parent_id')->where('is_active', true)->orderBy('sort_order');
    }

    public function cmsPage()
    {
        return $this->belongsTo(CmsPage::class);
    }

    public function getResolvedUrlAttribute(): string
    {
        if ($this->route_name) {
            return route($this->route_name);
        }
        if ($this->cmsPage) {
            return $this->cmsPage->url;
        }
        return $this->url ?? '#';
    }
}

// ──────────────────────────────────────────────────────────────────────────────
// FAQ MODEL
// ──────────────────────────────────────────────────────────────────────────────

class Faq extends Model
{
    protected $fillable = ['question', 'answer', 'category', 'sort_order', 'is_active'];
    protected $casts    = ['is_active' => 'boolean'];
}



// ──────────────────────────────────────────────────────────────────────────────
// BANNER MODEL
// ──────────────────────────────────────────────────────────────────────────────

class Banner extends Model
{
    protected $fillable = [
        'title', 'subtitle', 'image_path', 'cta_text', 'cta_url',
        'position', 'is_active', 'sort_order', 'starts_at', 'ends_at',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'starts_at' => 'datetime',
        'ends_at'   => 'datetime',
    ];

    public function isCurrentlyActive(): bool
    {
        if (!$this->is_active) return false;
        if ($this->starts_at && $this->starts_at->isFuture()) return false;
        if ($this->ends_at   && $this->ends_at->isPast())    return false;
        return true;
    }
}

// ──────────────────────────────────────────────────────────────────────────────
// TESTIMONIAL MODEL
// ──────────────────────────────────────────────────────────────────────────────

class Testimonial extends Model
{
    protected $fillable = [
        'name', 'photo', 'message', 'rating', 'city', 'is_active', 'sort_order',
    ];

    protected $casts = ['is_active' => 'boolean'];
}


// ──────────────────────────────────────────────────────────────────────────────
// ACTIVITY LOG MODEL
// ──────────────────────────────────────────────────────────────────────────────

class ActivityLog extends Model
{
    public $timestamps = false;
    protected $fillable = [
        'user_id', 'action', 'model_type', 'model_id',
        'old_values', 'new_values', 'ip_address', 'user_agent', 'created_at',
    ];

    protected $casts = [
        'old_values' => 'array',
        'new_values' => 'array',
        'created_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}

// ──────────────────────────────────────────────────────────────────────────────
// OTP TOKEN MODEL
// ──────────────────────────────────────────────────────────────────────────────

class OtpToken extends Model
{
    protected $fillable = [
        'user_id', 'type', 'token', 'expires_at', 'used_at',
    ];

    protected $casts = [
        'expires_at' => 'datetime',
        'used_at'    => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function isValid(): bool
    {
        return is_null($this->used_at) && $this->expires_at->isFuture();
    }
}

// ──────────────────────────────────────────────────────────────────────────────
// BACKUP LOG MODEL
// ──────────────────────────────────────────────────────────────────────────────

class BackupLog extends Model
{
    protected $fillable = [
        'filename', 'disk', 'size_bytes', 'status', 'error_message', 'completed_at',
    ];

    protected $casts = ['completed_at' => 'datetime'];
}



// ──────────────────────────────────────────────────────────────────────────────
// RELATIONSHIP MANAGER ASSIGNMENT MODEL
// ──────────────────────────────────────────────────────────────────────────────

class RelationshipManagerAssignment extends Model
{
    protected $fillable = [
        'rm_id', 'user_id', 'notes', 'is_active', 'assigned_at', 'unassigned_at',
    ];

    protected $casts = [
        'is_active'       => 'boolean',
        'assigned_at'     => 'datetime',
        'unassigned_at'   => 'datetime',
    ];

    public function rm()
    {
        return $this->belongsTo(User::class, 'rm_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}

// ──────────────────────────────────────────────────────────────────────────────
// RM INTERACTION MODEL
// ──────────────────────────────────────────────────────────────────────────────

class RmInteraction extends Model
{
    protected $fillable = [
        'rm_id', 'user_id', 'type', 'description', 'interacted_at',
    ];

    protected $casts = ['interacted_at' => 'datetime'];

    public function rm()
    {
        return $this->belongsTo(User::class, 'rm_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
