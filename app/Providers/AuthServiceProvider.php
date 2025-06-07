<?php

namespace App\Providers;

use App\Models\Post;
use App\Models\User;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    protected $policies = [];

    public function boot(): void
    {
        Gate::define('update-post', function (User $user, Post $post) {
            return $user->id === $post->author_id || $user->hasRole('admin');
        });

        Gate::define('delete-post', function (User $user, Post $post) {
            return $user->id === $post->author_id || $user->hasRole('admin');
        });
    }
}
