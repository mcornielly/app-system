@extends('auth.container.layout_login')

@section('login')
    <div class="row justify-content-center">
      <div class="col-md-8">
        <div class="card-group mb-0">
          <div class="card p-4">
            <form class="form-horizontal was-validated" method="POST" action="{{ route('login') }}">
                {{ csrf_field() }}
                <div class="card-body">
                  <h1>Acceder</h1>
                  <p class="text-muted">Control de acceso al sistema</p>
                  <div class="input-group mb-3{{ $errors->has('user' ? 'is-invalid' : '') }}">
                    <span class="input-group-addon"><i class="icon-user"></i></span>
                    <input type="text" value="{{ old('user_name') }}" name="user_name" id="user_name" class="form-control" placeholder="Usuario" autofocus>

                   {{--  {!!$errors->first('user_name','<span class="form-group invalid-feedback">:message</span>')!!} --}}
                  </div>
{{--                     @if ($errors->has('user_name'))
                        <span class="text-danger mb-3">{{ $errors->first('user_name') }}</span>
                    @endif --}}


                  <div class="input-group mb-4{{ $errors->has('password' ? 'is-invalid' : '') }}">
                    <span class="input-group-addon"><i class="icon-lock"></i></span>
                    <input type="password" name="password" id="password" class="form-control" placeholder="Password">
                   {{-- {!!$errors->first('password', '<span class="invalid-feedback">:message</span>')!!} --}}
                  </div>

{{--                     @if ($errors->has('password'))
                        <span class="invalid-feedback">{{ $errors->first('password') }}</span>
                    @endif --}}

                    <div class="alert alert-success">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li class="text-danger">{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>

                  <div class="row">
                    <div class="col-6">
                      <button type="submit" class="btn btn-primary px-4">Acceder</button>
                    </div>
                    <div class="col-6 text-right">
                      <button type="button" class="btn btn-link px-0">Olvidaste tu password?</button>
                    </div>
                  </div>
                </div>
            </form>
          </div>
          <div class="card text-white bg-primary py-5 d-md-down-none" style="width:44%">
            <div class="card-body text-center">
              <div>
                <h2>Sistema de Ventas IncanatoIT</h2>
                <p>Sistema de compras, Ventas desarrollado en PHP utilizando el Framework Laravel y Vue Js, con el gestor de base de datos MariaDB.</p>
                <a href="https://www.udemy.com/user/juan-carlos-arcila-diaz/" target="_blank" class="btn btn-primary active mt-3">Ver el curso!</a>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
@endsection
