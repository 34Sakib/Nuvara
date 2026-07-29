<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Faq;
use App\Models\PageContent;
use Illuminate\Http\Request;

class PageController extends Controller
{
    public function about(Request $request)
    {
        $locale = $request->header('Accept-Language', 'en');
        $page = PageContent::where('page_key', 'about')->first();

        if (!$page) {
            return response()->json(['error' => 'Page content not found'], 404);
        }

        $content = $page->content;

        // Localize content fields
        $localizedStats = array_map(function ($s) use ($locale) {
            return [
                'label' => is_array($s['label'] ?? null) ? ($s['label'][$locale] ?? $s['label']['en'] ?? '') : ($s['label'] ?? ''),
                'value' => $s['value'] ?? '',
                'icon' => $s['icon'] ?? 'Sparkles',
                'color' => $s['color'] ?? 'text-emerald-500 bg-emerald-500/10'
            ];
        }, $content['stats'] ?? []);

        $localizedTeam = array_map(function ($t) use ($locale) {
            return [
                'name' => $t['name'] ?? '',
                'role' => is_array($t['role'] ?? null) ? ($t['role'][$locale] ?? $t['role']['en'] ?? '') : ($t['role'] ?? ''),
                'image' => $t['image'] ?? '',
                'bio' => is_array($t['bio'] ?? null) ? ($t['bio'][$locale] ?? $t['bio']['en'] ?? '') : ($t['bio'] ?? '')
            ];
        }, $content['team'] ?? []);

        return response()->json([
            'hero_badge' => is_array($content['hero_badge'] ?? null) ? ($content['hero_badge'][$locale] ?? $content['hero_badge']['en'] ?? '') : ($content['hero_badge'] ?? ''),
            'hero_title' => is_array($content['hero_title'] ?? null) ? ($content['hero_title'][$locale] ?? $content['hero_title']['en'] ?? '') : ($content['hero_title'] ?? ''),
            'hero_subtitle' => is_array($content['hero_subtitle'] ?? null) ? ($content['hero_subtitle'][$locale] ?? $content['hero_subtitle']['en'] ?? '') : ($content['hero_subtitle'] ?? ''),
            'stats' => $localizedStats,
            'story_title' => is_array($content['story_title'] ?? null) ? ($content['story_title'][$locale] ?? $content['story_title']['en'] ?? '') : ($content['story_title'] ?? ''),
            'story_body' => is_array($content['story_body'] ?? null) ? ($content['story_body'][$locale] ?? $content['story_body']['en'] ?? '') : ($content['story_body'] ?? ''),
            'team' => $localizedTeam
        ]);
    }

    public function contact(Request $request)
    {
        $locale = $request->header('Accept-Language', 'en');
        $page = PageContent::where('page_key', 'contact')->first();

        if (!$page) {
            return response()->json(['error' => 'Page content not found'], 404);
        }

        $content = $page->content;

        $localizedCards = array_map(function ($c) use ($locale) {
            return [
                'title' => is_array($c['title'] ?? null) ? ($c['title'][$locale] ?? $c['title']['en'] ?? '') : ($c['title'] ?? ''),
                'value' => $c['value'] ?? '',
                'sub' => is_array($c['sub'] ?? null) ? ($c['sub'][$locale] ?? $c['sub']['en'] ?? '') : ($c['sub'] ?? ''),
                'icon' => $c['icon'] ?? 'Mail',
                'color' => $c['color'] ?? 'text-blue-500 bg-blue-500/10'
            ];
        }, $content['cards'] ?? []);

        return response()->json([
            'hero_badge' => is_array($content['hero_badge'] ?? null) ? ($content['hero_badge'][$locale] ?? $content['hero_badge']['en'] ?? '') : ($content['hero_badge'] ?? ''),
            'hero_title' => is_array($content['hero_title'] ?? null) ? ($content['hero_title'][$locale] ?? $content['hero_title']['en'] ?? '') : ($content['hero_title'] ?? ''),
            'hero_subtitle' => is_array($content['hero_subtitle'] ?? null) ? ($content['hero_subtitle'][$locale] ?? $content['hero_subtitle']['en'] ?? '') : ($content['hero_subtitle'] ?? ''),
            'cards' => $localizedCards
        ]);
    }

    public function faq(Request $request)
    {
        $locale = $request->header('Accept-Language', 'en');
        
        $query = Faq::where('status', true)->orderBy('sort_order');
        if ($request->filled('category') && $request->category !== 'all') {
            $query->where('category', $request->category);
        }

        $faqs = $query->get()->map(function ($faq) use ($locale) {
            return [
                'id' => $faq->id,
                'category' => $faq->category,
                'q' => $faq->getLocalized('question', $locale),
                'a' => $faq->getLocalized('answer', $locale)
            ];
        });

        return response()->json($faqs);
    }
}
