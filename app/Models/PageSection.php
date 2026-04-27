<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PageSection extends Model
{
    protected $fillable = [
        'page_id',
        'section_key',
        'heading',
        'subheading',
        'description',
        'image',
        'image_url',
        'button_text',
        'button_link',
        'order',
        'is_active'
    ];

    protected $appends = ['type', 'content'];

    public function getTypeAttribute()
    {
        return $this->section_key;
    }

    public function getContentAttribute()
    {
        return [
            'heading' => $this->heading,
            'title' => $this->heading, // Alias
            'subheading' => $this->subheading,
            'subtitle' => $this->subheading, // Alias
            'label' => $this->subheading, // Alias for some sections
            'description' => $this->description,
            'image' => $this->image,
            'image_url' => $this->image_url,
            'button_text' => $this->button_text,
            'button_link' => $this->button_link,
        ];
    }

    public function page()
    {
        return $this->belongsTo(Page::class);
    }
}
