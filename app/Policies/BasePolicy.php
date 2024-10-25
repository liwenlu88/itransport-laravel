<?php

namespace App\Policies;

use App\Helpers\Helper;


class BasePolicy
{
    public function create(): bool
    {
        return Helper::authorize('Create');
    }

    public function show(): bool
    {
        return Helper::authorize('Show');
    }

    public function edit(): bool
    {
        return Helper::authorize('Edit');
    }

    public function destroy(): bool
    {
        return Helper::authorize('Destroy');
    }
}
