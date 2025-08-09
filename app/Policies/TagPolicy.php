<?php

namespace App\Policies;

use App\Models\Tag;
use App\Models\Usuario; // Cambiar User por Usuario

class TagPolicy
{
    public function viewAny(Usuario $user): bool
    {
        return true;
    }

    public function view(Usuario $user, Tag $tag): bool
    {
        return $user->idUsuario === $tag->user_id; // Cambiar id por idUsuario
    }

    public function create(Usuario $user): bool
    {
        return true;
    }

    public function update(Usuario $user, Tag $tag): bool
    {
        return $user->idUsuario === $tag->user_id; // Cambiar id por idUsuario
    }

    public function delete(Usuario $user, Tag $tag): bool
    {
        return $user->idUsuario === $tag->user_id; // Cambiar id por idUsuario
    }

    public function restore(Usuario $user, Tag $tag): bool
    {
        return $user->idUsuario === $tag->user_id; // Cambiar id por idUsuario
    }

    public function forceDelete(Usuario $user, Tag $tag): bool
    {
        return $user->idUsuario === $tag->user_id; // Cambiar id por idUsuario
    }
}