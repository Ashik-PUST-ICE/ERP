<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Fill;

class QuestionsTemplateExport implements FromArray, WithHeadings, WithStyles, ShouldAutoSize
{
    public function array(): array
    {
        return [
            // Sample row 1 – MCQ
            [
                'Class 10', 'Mathematics', 'Algebra', 'Linear Equations',
                'MCQ', 'What is the value of x in 2x = 10?',
                '5', '3', '4', '2', '', 'a',
                'medium', 1, 2024, 'Because 2*5=10',
            ],
            // Sample row 2 – True/False
            [
                'Class 9', 'Physics', 'Force', '',
                'True/False', 'Newton\'s second law states F=ma.',
                '', '', '', '', '', 'True',
                'easy', 1, 2023, '',
            ],
            // Sample row 3 – Short
            [
                'Class 8', 'Bangla', 'Poem', '',
                'Short', 'Who wrote the poem "Amar Shonar Bangla"?',
                '', '', '', '', '', 'Rabindranath Tagore',
                'easy', 2, 2022, '',
            ],
        ];
    }

    public function headings(): array
    {
        return [
            'class', 'subject', 'chapter', 'topic',
            'question_type', 'question_text',
            'option_a', 'option_b', 'option_c', 'option_d', 'option_e', 'correct_answer',
            'difficulty', 'marks', 'year', 'explanation',
        ];
    }

    public function styles(Worksheet $sheet): array
    {
        return [
            1 => [
                'font' => ['bold' => true, 'color' => ['argb' => 'FFFFFFFF']],
                'fill' => [
                    'fillType'   => Fill::FILL_SOLID,
                    'startColor' => ['argb' => 'FF4F46E5'],
                ],
            ],
        ];
    }
}
