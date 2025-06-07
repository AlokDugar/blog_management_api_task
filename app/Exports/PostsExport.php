<?php

namespace App\Exports;

use App\Models\Post;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class PostsExport implements FromQuery, WithHeadings, WithMapping
{
    public function query()
    {
        return Post::query()->with(['author', 'category']);
    }

    public function headings(): array
    {
        return [
            'ID',
            'Title',
            'Author',
            'Category',
            'Created At',
        ];
    }

    public function map($post): array
    {
        return [
            $post->id,
            $post->title,
            $post->author->name,
            $post->category->name,
            $post->created_at->format('Y-m-d H:i:s'),
        ];
    }
}
