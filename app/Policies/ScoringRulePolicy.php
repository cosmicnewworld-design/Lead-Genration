<?php

namespace App\Policies;

use App\Models\ScoringRule;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class ScoringRulePolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function viewAny(User $user)
    {
        // Any authenticated user can view the list of their own tenant's rules.
        return true;
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\ScoringRule  $scoringRule
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function view(User $user, ScoringRule $scoringRule)
    {
        return $user->tenant_id === $scoringRule->tenant_id;
    }

    /**
     * Determine whether the user can create models.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function create(User $user)
    {
        // Any authenticated user can create a rule for their own tenant.
        return true;
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\ScoringRule  $scoringRule
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function update(User $user, ScoringRule $scoringRule)
    {
        return $user->tenant_id === $scoringRule->tenant_id;
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\ScoringRule  $scoringRule
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function delete(User $user, ScoringRule $scoringRule)
    {
        return $user->tenant_id === $scoringRule->tenant_id;
    }
}
