<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\DocumentAnalysis;
use App\Services\DocumentParserService;
use App\Services\GeminiAiService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class DocumentAnalysisController extends Controller
{
    protected DocumentParserService $parserService;
    protected GeminiAiService $aiService;

    public function __construct(DocumentParserService $parserService, GeminiAiService $aiService)
    {
        $this->parserService = $parserService;
        $this->aiService     = $aiService;
    }

    /**
     * List uploaded business documents & AI analytics hub
     */
    public function index(Request $request)
    {
        $query = DocumentAnalysis::where('user_id', auth()->id())
            ->orderBy('created_at', 'desc');

        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('judul', 'like', '%' . $request->search . '%')
                  ->orWhere('nama_file_asli', 'like', '%' . $request->search . '%');
            });
        }

        if ($request->filled('type')) {
            $query->where('file_type', strtolower($request->type));
        }

        $documents = $query->paginate(10);

        return view('admin.document_analytics.index', compact('documents'));
    }

    /**
     * Handle document upload, parse content, run AI analysis, and save to DB
     */
    public function store(Request $request)
    {
        $request->validate([
            'judul'    => 'required|string|max:255',
            'document' => 'required|file|max:15360', // Max 15MB
        ], [
            'judul.required'    => 'Judul / Topik Dokumen wajib diisi.',
            'document.required' => 'File dokumen wajib diunggah.',
            'document.max'      => 'Ukuran file dokumen maksimal 15MB.',
        ]);

        try {
            $file         = $request->file('document');
            $originalName = $file->getClientOriginalName();
            $ext          = strtolower($file->getClientOriginalExtension() ?: 'txt');

            // Format human-readable file size
            $bytes    = $file->getSize();
            $fileSize = $bytes >= 1048576
                ? number_format($bytes / 1048576, 1) . ' MB'
                : number_format($bytes / 1024, 0) . ' KB';

            // Store file
            $path     = $file->store('documents', 'public');
            $fullPath = storage_path('app/public/' . $path);

            // Parse document content
            $parsed = $this->parserService->parseDocument($fullPath, $ext);

            // Create DocumentAnalysis record in DB
            $doc = DocumentAnalysis::create([
                'user_id'             => auth()->id(),
                'judul'               => $request->judul,
                'nama_file_asli'      => $originalName,
                'file_path'           => $path,
                'file_type'           => $ext,
                'file_size'           => $fileSize,
                'total_baris_halaman' => $parsed['total_baris_halaman'] ?? 0,
                'headers'             => $parsed['headers'] ?? [],
                'preview_data'        => $parsed['preview_data'] ?? [],
                'extracted_text'      => $parsed['extracted_text'] ?? '',
            ]);

            // Run Gemini AI Analysis & persist to DB
            $analysis = $this->aiService->generateDocumentAnalysis($doc);
            $doc->update([
                'ai_analysis'    => $analysis,
                'ai_analyzed_at' => now(),
            ]);

            return redirect()->route('admin.document-analytics.show', $doc->id)
                ->with('success', 'Dokumen berhasil diunggah dan dianalisis oleh AI!');
        } catch (\Throwable $e) {
            Log::error("[DOCUMENT UPLOAD ERROR] " . $e->getMessage());
            return redirect()->back()
                ->withInput()
                ->with('error', 'Gagal memproses dokumen: ' . $e->getMessage());
        }
    }

    /**
     * Display executive document AI analysis report & preview data
     */
    public function show($id)
    {
        $document = DocumentAnalysis::where('user_id', auth()->id())->findOrFail($id);

        // Auto generate AI analysis if not present
        if (empty($document->ai_analysis)) {
            $analysis = $this->aiService->generateDocumentAnalysis($document);
            $document->update([
                'ai_analysis'    => $analysis,
                'ai_analyzed_at' => now(),
            ]);
            $document->refresh();
        }

        $aiAnalysis = $document->ai_analysis ?? [];

        return view('admin.document_analytics.show', compact('document', 'aiAnalysis'));
    }

    /**
     * Regenerate AI analysis for a document (clears old DB analysis and saves new result)
     */
    public function regenerate(Request $request, $id)
    {
        try {
            $document = DocumentAnalysis::where('user_id', auth()->id())->findOrFail($id);

            // Delete old AI analysis in DB
            $document->update([
                'ai_analysis'    => null,
                'ai_analyzed_at' => null,
            ]);

            // Generate fresh analysis
            $newAnalysis = $this->aiService->generateDocumentAnalysis($document);
            $document->update([
                'ai_analysis'    => $newAnalysis,
                'ai_analyzed_at' => now(),
            ]);

            if ($request->wantsJson()) {
                return response()->json([
                    'success'  => true,
                    'message'  => 'Analisa AI Dokumen berhasil diperbarui & disimpan.',
                    'analysis' => $newAnalysis,
                ]);
            }

            return redirect()->route('admin.document-analytics.show', $id)
                ->with('success', 'Analisa AI Dokumen berhasil diperbarui!');
        } catch (\Throwable $e) {
            if ($request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Gagal memperbarui analisa: ' . $e->getMessage(),
                ], 500);
            }

            return redirect()->back()->with('error', 'Gagal memperbarui analisa: ' . $e->getMessage());
        }
    }

    /**
     * Delete document record and file
     */
    public function destroy(Request $request, $id)
    {
        try {
            $document = DocumentAnalysis::where('user_id', auth()->id())->findOrFail($id);

            if (!empty($document->file_path)) {
                Storage::disk('public')->delete($document->file_path);
            }

            $document->delete();

            if ($request->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Dokumen dan analisa AI berhasil dihapus.',
                ]);
            }

            return redirect()->route('admin.document-analytics.index')
                ->with('success', 'Dokumen berhasil dihapus.');
        } catch (\Throwable $e) {
            if ($request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Gagal menghapus dokumen: ' . $e->getMessage(),
                ], 500);
            }

            return redirect()->back()->with('error', 'Gagal menghapus dokumen: ' . $e->getMessage());
        }
    }

    /**
     * Chatbot endpoint for interactive document Q&A and follow-up recommendations (Persisted in DB)
     */
    public function chat(Request $request, $id)
    {
        $request->validate([
            'question' => 'required|string|max:2000',
        ]);

        try {
            $document = DocumentAnalysis::where('user_id', auth()->id())->findOrFail($id);
            $history  = $document->chat_history ?? [];

            $reply = $this->aiService->chatDocument($document, $request->question, $history);

            if ($reply['success'] && !empty($reply['reply'])) {
                $history[] = [
                    'role'      => 'user',
                    'text'      => $request->question,
                    'timestamp' => now()->format('H:i'),
                ];
                $history[] = [
                    'role'      => 'assistant',
                    'text'      => $reply['reply'],
                    'timestamp' => now()->format('H:i'),
                ];

                $document->update([
                    'chat_history' => $history
                ]);

                return response()->json([
                    'success' => true,
                    'reply'   => $reply['reply'],
                    'history' => $history,
                ]);
            }

            return response()->json([
                'success' => false,
                'message' => $reply['error'] ?? 'Gagal memproses respon dari AI Gemini.',
            ], 500);
        } catch (\Throwable $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal memproses pertanyaan AI: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Clear persisted chat history for a document
     */
    public function clearChat(Request $request, $id)
    {
        try {
            $document = DocumentAnalysis::where('user_id', auth()->id())->findOrFail($id);
            $document->update(['chat_history' => null]);

            return response()->json([
                'success' => true,
                'message' => 'Histori percakapan AI berhasil dihapus.'
            ]);
        } catch (\Throwable $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal menghapus histori: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Rewind / Undo last chat response & question
     */
    public function rewindChat(Request $request, $id)
    {
        try {
            $document = DocumentAnalysis::where('user_id', auth()->id())->findOrFail($id);
            $history  = $document->chat_history ?? [];

            if (count($history) >= 2) {
                array_pop($history); // Remove assistant reply
                array_pop($history); // Remove user question
            } elseif (count($history) === 1) {
                array_pop($history);
            }

            $document->update(['chat_history' => $history]);

            return response()->json([
                'success' => true,
                'history' => $history,
                'message' => 'Percakapan berhasil di-rewind.'
            ]);
        } catch (\Throwable $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal rewind obrolan: ' . $e->getMessage()
            ], 500);
        }
    }
}
