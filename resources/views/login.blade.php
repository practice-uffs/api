<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
    <head>
        <meta charset="utf-8">
          <meta http-equiv="X-UA-Compatible" content="IE=edge">
          <meta name="viewport" content="width=device-width, initial-scale=1">
          <meta name="csrf-token" content="{{ csrf_token()}}">
          <script>window.Laravel = {csrfToken:'{{ csrf_token() }}'}</script>
          <title>Login - PRACTICE</title>
        
          <meta content="" name="description">
          <meta content="" name="keywords">
        
          <!-- Favicons -->
          <link href="{{ asset('assets/img/apple-touch-icon.png') }}" rel="apple-touch-icon">

          <!-- material icons-->
          <link href="https://fonts.googleapis.com/icon?family=Material+Icons"rel="stylesheet">
          <!-- bootstrapicon -->
          <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.3.0/font/bootstrap-icons.css">

          <!-- Bootstrap 5.0 CDN-->
          <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-BmbxuPwQa2lc/FVzBcNJ7UAyJxM6wuqIj61tLrc4wSX0szH/Ev+nYRRuWlolflfl" crossorigin="anonymous">

          <!-- Google Fonts -->
          <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i|Nunito:300,300i,400,400i,600,600i,700,700i|Poppins:300,300i,400,400i,500,500i,600,600i,700,700i" rel="stylesheet">
    
          <!-- Template Main CSS File -->
          <link href="{{ asset('css/app.css') }}" rel="stylesheet">

    </head>
    <body data-bs-spy="scroll" data-bs-target="#navbar" data-bs-offset="100">


        <div class="login container row mx-auto my-5 pt-5" >
            <!-- For Demo Purpose -->
            <div class="login-img col-md-5 pr-lg-5 mb-5 mb-md-0 mt-5">
                <img src="img/undow.co/login.svg" alt="" class="img-fluid">
                <h1>Conecte-se ao <b>PRACTICE</b></h1>
                <p class="font-italic text-muted mb-0">Utilize do seu idUFFS para conectar-se e aproveitar tudo que nossa plataforma oferece</p>
            </div>
        
            <form id="login-form" class="login-form col-md-6 mx-auto form-signin text-center " action="" method="post" >
                @csrf
                {{-- <input name="service" type="hidden" value="secret"> --}}
                <a href="/"><img class="mb-5" src="https://practice.uffs.cc/images/logo.png" alt="" width="272" ></a>
                <h1 class="h4 mb-3 font-weight-normal">Entre com seu idUFFS</h1>
                @if ($errors->any()) 
                    <div class="alert-error">
                        @foreach ($errors->all() as $error)
                            {{ $error }}
                        @endforeach
                    </div>
                @endif
        
                
                <!-- idUFFS -->
                {{-- <label for="inputEmail" class="sr-only">idUFFS</label> --}}
                <div class="input-group col-lg-6 mb-4">
                    <div class="input-group-prepend">
                        <span class="input-group-text bg-white px-4 border-md border-right-0">
                            <i class="bi bi-person text-muted"></i>
                        </span>
                    </div>
                    <input type="text" id="inputEmail" placeholder="idUFFS" required="" autofocus=""
                        name="username" value="{{ old('username') }}" placeholder="idUFFS"
                        class="form-control validate @error('username') is-invalid @enderror card__input" >
                    
                </div>
                <!-- Password -->
                {{-- <label for="inputPassword" class="sr-only">Senha</label> --}}
                <div class="input-group col-lg-6 mb-4">
                    <div class="input-group-prepend">
                        <span class="input-group-text bg-white px-4 border-md border-right-0">
                            <i class="bi bi-lock-fill text-muted"></i>
                        </span>
                    </div>
                    <input type="password" id="inputPassword" name="password"  placeholder="Senha" required="" placeholder="Senha"
                           class="form-control @error('password') is-invalid @enderror validate card__input"
                           autocomplete="current-password">
                </div>
        
                <button id="btn-submit" class="btn btn-lg btn-block btn-warning col-12" type="submit">ENTRAR</button>
                <button id="btn-loading" class="btn btn-lg btn-block btn-warning col-12" type="button" disabled>
                    <span class="spinner-border spinner-border-md" role="status" aria-hidden="true"></span>
                    Entrando...
                  </button>

                <!-- Divider Text -->
                <div class="form-group col-lg-12 mx-auto d-flex align-items-center my-4">
                    <div class="border-bottom w-100 ml-5"></div>
                    <div class="border-bottom w-100 mr-5"></div>
                </div>
                <nav class="login-help-links text-center mx-auto" role="navigation">
                    <a href="https://id.uffs.edu.br/id/XUI/?realm=/#forgotUsername/">Não sabe seu IdUFFS?</a>
                    <div>|</div>
                    <a href="https://id.uffs.edu.br/id/XUI/?realm=/#passwordReset/">Esqueceu a Senha?</a>
                    <div>|</div>
                    <a href="https://ati.uffs.edu.br/public.pl?CategoryID=17">Ajuda</a>
                </nav>
            </form>
        </div>
        <!-- BoOtstrap 5.0 JS CDN-->
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta2/dist/js/bootstrap.bundle.min.js" integrity="sha384-b5kHyXgcpbZJO/tY9Ul7kGkf1S0CWuKcCD38l8YkeH8z8QjE0GmW1gYU5S9FOnJ0" crossorigin="anonymous"></script>
    </body>
    <script src="{{ asset('js/app.js') }}"></script>
</html>
