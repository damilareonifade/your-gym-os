<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Model;

class Team extends Model
{
    use HasUlids;

    protected $casts = [
        'user_id' => 'string',
        'personal_team' => 'boolean',
    ];

    protected $guarded = ["id"];

    /**
     * Get the owner of the team.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function owner()
    {
        return $this->belongsTo(Admin::class, 'user_id');
    }

    /**
     * Get all of the team's users including its owner.
     *
     * @return \Illuminate\Support\Collection
     */
    public function allUsers()
    {
        return $this->users->merge([$this->owner]);
    }

    /**
     * Get all of the users that belong to the team.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function users()
    {
        return $this->belongsToMany(Admin::class, 'team_members', 'team_id', 'user_id')
            ->withPivot('role')
            ->withTimestamps()
            ->as('membership');
    }

    /**
     * Determine if the given user belongs to the team.
     *
     * @param  \App\Models\Admin  $user
     * @return bool
     */
    public function hasUser($user)
    {
        return $this->users->contains($user) || $user->ownsTeam($this);
    }

    /**
     * Determine if the given email address belongs to a user on the team.
     *
     * @param  string  $email
     * @return bool
     */
    public function hasUserWithEmail(string $email)
    {
        return $this->allUsers()->contains(function ($user) use ($email) {
            return $user->email === $email;
        });
    }

    /**
     * Determine if the given user has the given permission on the team.
     *
     * @param  \App\Models\Admin  $user
     * @param  string  $permission
     * @return bool
     */
    public function userHasPermission($user, $permission)
    {
        return $user->hasPermissionTo($this, $permission);
    }

    /**
     * Get all of the pending user invitations for the team.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function teamInvitations()
    {
        return $this->hasMany(TeamInvitation::class);
    }

    public function allMembers()
    {
        return $this->belongsToMany(Admin::class, 'team_members')
            ->withPivot('role')
            ->withTimestamps()
            ->as('membership');
    }

    /* New Method to Get All Members
     */
    public function members()
    {
        return $this->allMembers()->with('user')->get();
    }

    /**
     * Remove the given user from the team.
     *
     * @param  \App\Models\Admin  $user
     * @return void
     */
    public function removeUser($user)
    {
        if ($user->current_team_id === $this->id) {
            $user->forceFill([
                'current_team_id' => null,
            ])->save();
        }

        $this->users()->detach($user);
    }

    /**
     * Purge all of the team's resources.
     *
     * @return void
     */
    public function purge()
    {
        $this->owner()->where('current_team_id', $this->id)
            ->update(['current_team_id' => null]);

        $this->users()->where('current_team_id', $this->id)
            ->update(['current_team_id' => null]);

        $this->users()->detach();

        $this->delete();
    }


}