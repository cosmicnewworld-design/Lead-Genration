<?php

namespace App\Services;

use App\Repositories\LeadRepository;
use Illuminate\Http\UploadedFile;

class LeadImportService
{
    public function __construct(private LeadRepository $leadRepository)
    {
    }

    /**
     * Import leads from a CSV file.
     *
     * Supported headers (case-insensitive):
     * - name, email, phone, company, job_title, website, source
     *
     * @return array{imported:int, skipped:int, errors:array<int,string>}
     */
    public function importCsv(UploadedFile $file, array $defaults = []): array
    {
        $imported = 0;
        $skipped = 0;
        $errors = [];

        $path = $file->getRealPath();
        if (!$path) {
            return ['imported' => 0, 'skipped' => 0, 'errors' => ['Unable to read uploaded file.']];
        }

        $handle = fopen($path, 'r');
        if (!$handle) {
            return ['imported' => 0, 'skipped' => 0, 'errors' => ['Unable to open uploaded file.']];
        }

        $header = null;
        $rowNum = 0;

        while (($row = fgetcsv($handle)) !== false) {
            $rowNum++;

            // Skip empty lines
            if (!$row || count(array_filter($row, fn ($v) => trim((string) $v) !== '')) === 0) {
                continue;
            }

            if ($header === null) {
                // Treat first row as header if it contains "email" or "name"
                $lower = array_map(fn ($v) => strtolower(trim((string) $v)), $row);
                if (in_array('email', $lower, true) || in_array('name', $lower, true)) {
                    $header = $lower;
                    continue;
                }

                // Otherwise assume fixed order
                $header = ['name', 'email', 'phone', 'company', 'job_title', 'website', 'source'];
            }

            $data = $defaults;
            foreach ($header as $i => $key) {
                if (!isset($row[$i])) {
                    continue;
                }
                $value = trim((string) $row[$i]);
                if ($value === '') {
                    continue;
                }
                $data[$key] = $value;
            }

            // Minimum required: email or name+company (but app validation expects email on create)
            if (empty($data['email']) || !filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
                $skipped++;
                $errors[] = "Row {$rowNum}: invalid or missing email.";
                continue;
            }
            if (empty($data['name'])) {
                $data['name'] = $data['email'];
            }

            try {
                $this->leadRepository->create($data);
                $imported++;
            } catch (\Throwable $e) {
                $skipped++;
                $errors[] = "Row {$rowNum}: " . $e->getMessage();
            }
        }

        fclose($handle);

        return [
            'imported' => $imported,
            'skipped' => $skipped,
            'errors' => $errors,
        ];
    }
}

