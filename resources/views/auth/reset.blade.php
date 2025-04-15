<form method="POST" action="{{ route('password.update') }}">
    @csrf
    <input type="hidden" name="token" value="{{ $token }}">
    <div class="form-group">
        <label for="email">Correo Electrónico</label>
        <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus />
    </div>
    <div class="form-group">
        <label for="password">Nueva Contraseña</label>
        <input id="password" type="password" name="password" required />
    </div>
    <div class="form-group">
        <label for="password_confirmation">Confirmar Nueva Contraseña</label>
        <input id="password_confirmation" type="password" name="password_confirmation" required />
    </div>
    <button type="submit" class="btn btn-primary">Restablecer Contraseña</button>
</form>
