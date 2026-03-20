<?php

namespace App\Services\Core;

use App\Models\Document;
use Illuminate\Support\Facades\Storage;

class DocumentsService
{
    protected string $disk = 'private';

    public function listByClient(int $clientId): array
    {
        return Document::where('client_id', $clientId)
            ->orderByDesc('created_at')
            ->get()
            ->toArray();
    }

    public function listByCase(int $caseId): array
    {
        return Document::where('case_id', $caseId)
            ->orderByDesc('created_at')
            ->get()
            ->toArray();
    }

    public function upload(array $data, $file): Document
    {
        $path = $file->store(
            "documents/{$data['client_id']}/" . now()->format('Y/m'),
            $this->disk
        );

        return Document::create([
            'client_id' => $data['client_id'],
            'case_id' => $data['case_id'] ?? null,
            'type' => $data['type'] ?? 'other',
            'name' => $data['name'] ?? $file->getClientOriginalName(),
            'original_filename' => $file->getClientOriginalName(),
            'file_path' => $path,
            'file_size' => $file->getSize(),
            'mime_type' => $file->getMimeType(),
            'uploaded_by' => auth()->id(),
            'expiry_date' => $data['expiry_date'] ?? null,
        ]);
    }

    public function getDownloadUrl(int $id): string
    {
        $doc = Document::findOrFail($id);
        return Storage::disk($this->disk)->temporaryUrl($doc->file_path, now()->addMinutes(30));
    }

    public function delete(int $id): void
    {
        $doc = Document::findOrFail($id);
        Storage::disk($this->disk)->delete($doc->file_path);
        $doc->delete();
    }

    public function getExpiringDocuments(int $days = 30): array
    {
        return Document::whereNotNull('expiry_date')
            ->where('expiry_date', '<=', now()->addDays($days)->toDateString())
            ->where('expiry_date', '>=', now()->toDateString())
            ->with('client')
            ->orderBy('expiry_date')
            ->get()
            ->toArray();
    }

    public function getDocumentTypes(): array
    {
        return [
            'passport' => 'Passport',
            'visa' => 'Visa',
            'residence_card' => 'Karta Pobytu',
            'work_permit' => 'Work Permit (Zezwolenie)',
            'pesel' => 'PESEL Certificate',
            'meldunek' => 'Meldunek (Registration)',
            'contract' => 'Employment Contract',
            'tax_document' => 'Tax Document',
            'diploma' => 'Diploma / Education',
            'marriage_cert' => 'Marriage Certificate',
            'birth_cert' => 'Birth Certificate',
            'bank_statement' => 'Bank Statement',
            'insurance' => 'Insurance Document',
            'photo' => 'Photo (3.5x4.5)',
            'power_of_attorney' => 'Power of Attorney',
            'application_form' => 'Application Form',
            'other' => 'Other',
        ];
    }
}
