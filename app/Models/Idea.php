<?php

namespace App\Models;

use App\QueryFilters\ideasFilter;
use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Pipeline\Pipeline;

class Idea extends Model
{
    use HasFactory,Sluggable;

    protected $guarded = [];
    protected $perPage = 10;
    /**
     * @return BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * @return array
     */
    public function sluggable(): array
    {
        return [
            'slug' => [
                'source' => 'title'
            ]
        ];
    }

    /**
     * @return BelongsTo
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * @return BelongsTo
     */
    public function status(): BelongsTo
    {
        return $this->belongsTo(Status::class);
    }

    /**
     * @return belongsToMany
     */
    public function votes(): belongsToMany
    {
        return $this->belongsToMany(User::class,'votes');
    }

    /**
     * @return HasMany
     */
    public function comments(): HasMany
    {
        return $this->hasMany(Comment::class);
    }

    /**
     * @param User|null $user
     * @return bool
     */
    public function isVotedByUser(?User $user): bool
    {
        if (!$user){
            return false;
        }

        return $this->votes()
                ->where('user_id', $user->id)
                ->exists();
    }

    public function vote(User $user)
    {
        if ($this->isVotedByUser($user)) {
            return;
        }

        Vote::create([
            'idea_id' => $this->id,
            'user_id' => $user->id
        ]);
    }

    public function removeVote(User $user)
    {
        Vote::where('idea_id',$this->id)
            ->where('user_id',$user->id)
            ->delete();
    }

    public function scopeFilter($query){
        return app(Pipeline::class)->send($query)->through([
            IdeasFilter::class
        ])->thenReturn();
    }
}
