<?php

namespace App\Providers;

use App\Models\ListOfLists;
use App\Models\TodoItem;
use App\Models\TodoList;
use App\Policies\ListOfListsPolicy;
use App\Policies\TodoItemPolicy;
use App\Policies\TodoListPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;
use Laravel\Passport\Passport;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        TodoItem::class => TodoItemPolicy::class,
        TodoList::class => TodoListPolicy::class,
        ListOfLists::class => ListOfListsPolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        Passport::routes();
    }
}
