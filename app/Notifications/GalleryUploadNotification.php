<?php
// app/Notifications/GalleryUploadNotification.php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class GalleryUploadNotification extends Notification implements ShouldQueue
{
    use Queueable;

    protected $galleryData;
    protected $uploadedBy;
    protected $count;

    /**
     * Create a new notification instance.
     */
    public function __construct($galleryData, $uploadedBy, $count)
    {
        $this->galleryData = $galleryData;
        $this->uploadedBy = $uploadedBy;
        $this->count = $count;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via($notifiable)
    {
        return ['database'];
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toDatabase($notifiable): array
    {
        $titleInfo = $this->galleryData['title_name'] ?? 'Untitled Gallery';
        $folderInfo = $this->galleryData['folder_id'] ?? 'Uncategorized';
        
        // Determine location/context info
        $locationInfo = $this->getLocationInfo();
        
        return [
            'type' => 'gallery_upload',
            'title' => 'New Gallery Images Uploaded',
            'message' => "{$this->count} new image(s) uploaded to '{$titleInfo}' ({$folderInfo}) {$locationInfo}",
            'institute_id' => $this->galleryData['institute_id'],
            'branch_id' => $this->galleryData['branch_id'] ?? null,
            'department_category_id' => $this->galleryData['department_category_id'] ?? null,
            'department_id' => $this->galleryData['department_id'] ?? null,
            'course_id' => $this->galleryData['course_id'] ?? null,
            'folder_id' => $this->galleryData['folder_id'],
            'title_name' => $this->galleryData['title_name'] ?? null,
            'uploaded_by' => [
                'id' => $this->uploadedBy->id,
                'name' => $this->uploadedBy->name ?? $this->uploadedBy->email,
                'type' => $this->getUserType($this->uploadedBy),
            ],
            'count' => $this->count,
            'first_image' => $this->galleryData['first_image'] ?? null,
            'url' => route('institute.gallery.index', ['folder' => $this->galleryData['folder_id']]),
            'time' => now()->toDateTimeString(),
            'action_required' => false,
        ];
    }

    /**
     * Get location info based on department/course
     */
    protected function getLocationInfo(): string
    {
        $parts = [];
        
        if ($this->galleryData['course_id']) {
            $parts[] = "Course: {$this->galleryData['course_id']}";
        } elseif ($this->galleryData['department_id']) {
            $parts[] = "Department: {$this->galleryData['department_id']}";
        } elseif ($this->galleryData['department_category_id']) {
            $parts[] = "Category: {$this->galleryData['department_category_id']}";
        }
        
        return !empty($parts) ? 'in ' . implode(' - ', $parts) : 'in General Gallery';
    }

    /**
     * Get user type for the uploader
     */
    protected function getUserType($user): string
    {
        $userClass = get_class($user);
        
        if (str_contains($userClass, 'InstituteAdmin')) {
            return 'Admin';
        } elseif (str_contains($userClass, 'Employee')) {
            return 'Employee';
        } elseif (str_contains($userClass, 'Student')) {
            return 'Student';
        }
        
        return 'User';
    }
}