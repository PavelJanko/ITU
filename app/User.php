<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * Get the comments of a user.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function comments()
    {
        return $this->hasMany(Comment::class, 'author_id');
    }

    /**
     * Get the documents that a user can access.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function documents()
    {
        return $this->hasMany(Document::class, 'owner_id');
    }

    /**
     * Get the folders that a user can access.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function folders()
    {
        return $this->hasMany(Folder::class, 'owner_id');
    }

    /**
     * Get the groups a user belongs to.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function groups()
    {
        return $this->belongsToMany(Group::class);
    }

    public function canAccessDocument(Document $document)
    {
        if($document->owner->id == $this->id)
            return true;

        foreach($document->groups as $group)
            if($this->groups->contains($group))
                return true;

        if($document->parent != NULL)
            return $this->canAccessFolder($document->parent);
        else
            return false;
    }

    public function canAccessFolder(Folder $folder)
    {
        if($folder->owner->id == $this->id)
            return true;

        foreach($folder->groups as $group)
            if($this->groups->contains($group))
                return true;

        if($folder->parent != NULL)
            return $this->canAccessFolder($folder->parent);
        else
            return false;
    }
}
