<?php

namespace App\Policies;

use App\Models\Note;
use App\Models\Usuario; // Cambiar User por Usuario

class NotePolicy
{
    public function viewAny(Usuario $user): bool
    {
        return true;
    }

    public function view(Usuario $user, Note $note): bool
    {
        return $user->idUsuario === $note->user_id; // Cambiar id por idUsuario
    }

    public function create(Usuario $user): bool
    {
        return true;
    }

    public function update(Usuario $user, Note $note): bool
    {
        return $user->idUsuario === $note->user_id; // Cambiar id por idUsuario
    }

    public function delete(Usuario $user, Note $note): bool
    {
        return $user->idUsuario === $note->user_id; // Cambiar id por idUsuario
    }

    public function restore(Usuario $user, Note $note): bool
    {
        return $user->idUsuario === $note->user_id; // Cambiar id por idUsuario
    }

    public function forceDelete(Usuario $user, Note $note): bool
    {
        return $user->idUsuario === $note->user_id; // Cambiar id por idUsuario
    }
}