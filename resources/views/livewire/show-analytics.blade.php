<main id="main" class="main">
    
    <div class="pagetitle">
        <h1 class="pl-4 pt-4 pb-4">
            <svg xmlns="http://www.w3.org/2000/svg" width="50" height="50" fill="currentColor" class="bi bi-bar-chart-line" viewBox="0 0 16 16">

                <path d="M11 2a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1v12h.5a.5.5 0 0 1 0 1H.5a.5.5 0 0 1 0-1H1v-3a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1v3h1V7a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1v7h1V2zm1 12h2V2h-2v12zm-3 0V7H7v7h2zm-5 0v-3H2v3h2z"/>
            </svg>
             |  Analytics
            <p class="gradient">PRACTICE</p>
        </h1>
            
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ Route('home')}}">Home</a></li>
                <li class="breadcrumb-item active">Dashboard</li>
            </ol>
        </nav>
    </div>

    <section class="section dashboard">
      <div class="row">

        <div class="col-lg-8">
          <div class="row">

            <div class="col-12">
                <div class="card recent-sales overflow-auto">
                    <div class="card-body">
                        <div class="d-flex justify-content-between pb-4">
                            <h5 class="card-title">Todas as avaliações<span></span></h5>

                            <div class="dropdown">
                                <button class="btn btn-primary dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    Action
                                </button>
                                <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                    <a class="dropdown-item" href="#" wire:click="">aura_feedback
                                    <a class="dropdown-item" href="#" wire:click="">good_health_feedback</a>
                                    <a class="dropdown-item" href="#" wire:click="">athletic_activities_feedback</a>
                                </div>
                            </div>
                        </div>

                        <table class="table-body table table-striped table-bordered table-sm"">
                            <thead>
                                <tr>
                                    <th scope="col">Id</th>
                                    <th scope="col">Usuário</th>
                                    <th scope="col">Action</th>
                                    <th scope="col">Key</th>
                                    <th scope="col">Value</th>
                                    <th scope="col">Rate</th>
                                </tr>
                            </thead>
                            <tbody >
                                @foreach ($analytics as $analytic)
                                <tr>
                                    <td>{{$analytic->id}}</td>
                                    <td>{{$analytic->user_id}}</td>
                                    <td>{{$analytic->action}}</td>
                                    <td>{{$analytic->key}}</td>
                                    <td>{{$analytic->value}}</td>
                                    <td><span class="badge bg-success">{{$analytic->rate}}</span></td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>

                    </div>

                </div>
            </div>
          </div>
        </div><!-- Coluna da esquerda -->

        <div class="col-lg-4">

          <div class="card px-4">
            <h4 class="text-center pt-4" >Objetivo desta tela:</h1>

            <p class="pt-4 px-2 ">&nbsp;&nbsp;Trazer os feedbacks gerados por ablicativos do PRACTICE, bem como: O feedback das respostas da Aura</p>

            <p class="pt-4 px-2 ">&nbsp;&nbsp;Futuramente, esta tela também pode ser utilizada para alterar as respostas da Aura que foram avaliadas negativamente :D</p>
                
          </div>

        </div><!-- Coluna da direita -->

      </div>
    </section>

  </main>