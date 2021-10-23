<?php

namespace App\Announcements\Entity;

use ClientX\Entity\Timestamp;
use ClientX\Helpers\Str;
use function ClientX\d;

class Announcement
{

    private ?int $id = null;
    private string $content = '';
    private string $title = '';
    private bool $published = false;
    private bool $pinned = false;
    private string $thumbnail = 'default.png';
    use Timestamp;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(int $id)
    {
        $this->id = $id;
    }

    public function getContent(): ?string
    {
        return nl2br($this->content);
    }

    public function setContent(string $content)
    {
        $this->content = d($content);
    }

    public function getPublished(): ?bool
    {
        return $this->published;
    }

    public function setPublished(bool $published)
    {
        $this->published = $published;
    }

    public function isPublished(): bool
    {
        return $this->getPublished() === true;
    }

    public function excerpt(): string
    {
        return Str::excerpt($this->content);
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title)
    {
        $this->title = $title;
    }

    public function getPinned(): bool
    {
        return $this->pinned;
    }

    public function isPinned(): bool
    {
        return $this->pinned;
    }

    public function setPinned(bool $pinned): void
    {
        $this->pinned = $pinned;
    }

    public function getThumbnail(): string
    {
        return $this->thumbnail;
    }

    public function setThumbnail($thumbnail): void
    {
        if ($thumbnail != null) {
            $this->thumbnail = $thumbnail;
        }
    }

    public function getThumbnailUrl(): string
    {
        if ($this->thumbnail === 'default.png') {
            return 'https://clientxcms.com/Themes/CLIENTXCMS/images/hosting/bg.png';
        }
        return "/uploads/announcements/" . $this->thumbnail;
    }
}
