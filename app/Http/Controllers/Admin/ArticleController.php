<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Article;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;

class ArticleController extends Controller
{
    /**
     * Display a listing of the articles
     */
    public function index()
    {
        try {
            $articles = Article::with('user')->latest()->get();
            
            if (auth()->check() && auth()->user()->role === 'admin') {
                return view('admin.articles.index', compact('articles'));
            }
            
            return view('user.articles.index', compact('articles'));
            
        } catch (\Exception $e) {
            Log::error('Article index error: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Gagal memuat daftar artikel.');
        }
    }

    /**
     * Show the form for creating a new article
     */
    public function create()
    {
        return view('admin.articles.create');
    }

    /**
     * Store a newly created article in storage
     */
    public function store(Request $request)
    {
        Log::info('=== ARTICLE STORE START ===');

        $request->validate([
            'title' => 'required|string|max:255',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
            'excerpt' => 'required|string|max:200',
            'content' => 'required|string',
        ]);

        Log::info('Validation passed');

        try {
            // Simpan gambar
            $imagePath = $request->file('image')->store('articles', 'public');
            Log::info('Image stored: ' . $imagePath);

            // Generate slug
            $slug = $this->generateUniqueSlug($request->title);

            // Create article
            $article = Article::create([
                'title' => $request->title,
                'slug' => $slug,
                'image' => $imagePath,
                'excerpt' => $request->excerpt,
                'content' => $request->content,
                'user_id' => auth()->id() ?? 1,
                'is_published' => true,
                'views_count' => 0,
            ]);

            Log::info('Article created successfully: ' . $article->id);

            return redirect()->route('admin.literacy')->with('success', 'Artikel berhasil ditambahkan!');
            
        } catch (\Exception $e) {
            Log::error('Article store failed: ' . $e->getMessage());
            
            return redirect()->back()
                ->with('error', 'Gagal menambahkan artikel: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Display the specified article
     */
    public function show($id)
    {
        try {
            $article = Article::with('user')->findOrFail($id);
            
            // Increment view count
            $article->increment('views_count');
            
            return view('articles.show', compact('article'));
            
        } catch (\Exception $e) {
            Log::error('Article show error: ' . $e->getMessage());
            return redirect()->route('admin.literacy')->with('error', 'Artikel tidak ditemukan.');
        }
    }

    /**
     * Show the form for editing the specified article
     */
    public function edit($id)
    {
        try {
            $article = Article::findOrFail($id);
            
            // Authorization check - lebih fleksibel
            if (auth()->user()->role !== 'admin' && $article->user_id !== auth()->id()) {
                abort(403, 'Unauthorized action.');
            }

            return view('admin.articles.edit', compact('article'));
            
        } catch (\Exception $e) {
            Log::error('Article edit error: ' . $e->getMessage());
            return redirect()->route('admin.literacy')->with('error', 'Artikel tidak ditemukan.');
        }
    }

    /**
     * Update the specified article in storage
     */
    public function update(Request $request, $id)
{
    $request->validate([
        'title' => 'required|string|max:255',
        'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        'excerpt' => 'required|string|max:200',
        'content' => 'required|string',
        'is_published' => 'sometimes|boolean',
    ]);

    try {
        $article = Article::findOrFail($id);
        
        // Authorization check
        if (auth()->user()->role !== 'admin' && $article->user_id !== auth()->id()) {
            abort(403, 'Unauthorized action.');
        }

        $updateData = [
            'title' => $request->title,
            'excerpt' => $request->excerpt,
            'content' => $request->content,
            'is_published' => $request->has('is_published') ? (bool)$request->is_published : $article->is_published,
        ];

        // Update slug if title changed
        if ($article->title !== $request->title) {
            $updateData['slug'] = $this->generateUniqueSlug($request->title, $article->id);
        }

        // Update image if provided
        if ($request->hasFile('image')) {
            // Delete old image
            if ($article->image && Storage::disk('public')->exists($article->image)) {
                Storage::disk('public')->delete($article->image);
            }
            
            // Upload new image
            $imagePath = $request->file('image')->store('articles', 'public');
            $updateData['image'] = $imagePath;
        }

        $article->update($updateData);

        return redirect()->route('admin.literacy')->with('success', 'Artikel berhasil diperbarui!');
        
    } catch (\Exception $e) {
        Log::error('Article update error: ' . $e->getMessage());
        return redirect()->back()->with('error', 'Gagal memperbarui artikel: ' . $e->getMessage())->withInput();
    }
}

    /**
     * Remove the specified article from storage
     */
    public function destroy($id)
    {
        try {
            $article = Article::findOrFail($id);
            
            // Authorization check - lebih fleksibel
            if (auth()->user()->role !== 'admin' && $article->user_id !== auth()->id()) {
                abort(403, 'Unauthorized action.');
            }

            $articleTitle = $article->title;

            // Delete image from storage
            if ($article->image && Storage::disk('public')->exists($article->image)) {
                Storage::disk('public')->delete($article->image);
            }
            
            // Delete article
            $article->delete();

            return redirect()->route('admin.literacy')->with('success', 'Artikel "' . $articleTitle . '" berhasil dihapus!');
            
        } catch (\Exception $e) {
            Log::error('Article destroy error: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Gagal menghapus artikel. Silakan coba lagi.');
        }
    }

    /**
     * Generate unique slug for articles
     */
    private function generateUniqueSlug($title, $excludeId = null)
    {
        $slug = Str::slug($title);
        $originalSlug = $slug;
        $counter = 1;

        do {
            $query = Article::where('slug', $slug);
            
            if ($excludeId) {
                $query->where('id', '!=', $excludeId);
            }
            
            if (!$query->exists()) {
                break;
            }
            
            $slug = $originalSlug . '-' . $counter;
            $counter++;
            
        } while (true);

        return $slug;
    }

    /**
     * Toggle publish status
     */
    public function togglePublish($id)
    {
        try {
            $article = Article::findOrFail($id);
            
            // Authorization check - lebih fleksibel
            if (auth()->user()->role !== 'admin' && $article->user_id !== auth()->id()) {
                abort(403, 'Unauthorized action.');
            }

            $article->update([
                'is_published' => !$article->is_published
            ]);

            $status = $article->is_published ? 'dipublikasikan' : 'disembunyikan';

            return redirect()->back()->with('success', 'Artikel berhasil ' . $status . '!');
            
        } catch (\Exception $e) {
            Log::error('Article toggle publish error: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Gagal mengubah status artikel.');
        }
    }
}