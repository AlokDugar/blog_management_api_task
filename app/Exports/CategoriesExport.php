<?php

namespace App\Exports;

use App\Models\Category;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class CategoriesExport implements FromCollection, WithHeadings, WithMapping
{
    public function collection()
    {
        return Category::all();
    }

    public function headings(): array
    {
        return [
            'ID',
            'Name',
            'Slug',
            'Posts Count',
            'Created At',
        ];
    }

    public function map($category): array
    {
        return [
            $category->id,
            $category->name,
            $category->slug,
            $category->posts()->count(),
            $category->created_at->format('Y-m-d H:i:s'),
        ];
    }
}
