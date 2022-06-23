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
                            <div>
                                <p class="d-inline pr-2">Action: </p>
                                <div class="dropdown d-inline">
                                    <button class="btn btn-primary dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        {{ $searchParams['action']}}
                                    </button>
                                    <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                        <a class="dropdown-item" href="#" wire:click="query('aura_feedback','{{$searchParams['order_by']}}','{{$searchParams['group_by']}}')"> aura_feedback
                                        <a class="dropdown-item" href="#" wire:click="query('other_action_1','{{$searchParams['order_by']}}','{{$searchParams['group_by']}}')">other_action_1</a>
                                        <a class="dropdown-item" href="#" wire:click="query('other_action_2','{{$searchParams['order_by']}}','{{$searchParams['group_by']}}')">other_action_2</a>
                                    </div>
                                </div>
                            </div>

                            <div>
                                <p class="d-inline pr-2">Group By: </p>
                                <div class="dropdown d-inline">
                                    <button class="btn btn-primary dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        {{ $searchParams['group_by']}}
                                    </button>
                                    <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                        <a class="dropdown-item" href="#" wire:click="query('{{ $searchParams['action']}}','{{$searchParams['order_by']}}','none')">none</a>
                                        <a class="dropdown-item" href="#" wire:click="query('{{ $searchParams['action']}}','{{$searchParams['order_by']}}','user_id')">user_id</a>
                                        <a class="dropdown-item" href="#" wire:click="query('{{ $searchParams['action']}}','{{$searchParams['order_by']}}','key')"> key</a>
                                    </div>
                                </div>
                            </div>

                        </div>

                        <table class="table-body table table-striped table-bordered table-sm"">
                            <thead>
                                <tr>
                                    <th scope="col"><a href="#" wire:click="query('{{ $searchParams['action']}}','id','{{$searchParams['group_by']}}')" >    Id</a></th>
                                    <th scope="col"><a href="#" wire:click="query('{{ $searchParams['action']}}','user_id','{{$searchParams['group_by']}}')" >  Usuário</a></th>
                                    <th scope="col"><a href="#" wire:click="query('{{ $searchParams['action']}}','action','{{$searchParams['group_by']}}')" >Action</a></th>
                                    <th scope="col"><a href="#" wire:click="query('{{ $searchParams['action']}}','key','{{$searchParams['group_by']}}')" >   Key</a></th>
                                    <th scope="col"><a href="#" wire:click="query('{{ $searchParams['action']}}','value','{{$searchParams['group_by']}}')" > Value</a></th>
                                    <th scope="col"><a href="#" wire:click="query('{{ $searchParams['action']}}','rate','{{$searchParams['group_by']}}')" >  Rate</a></th>
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

                                    <td class="text-center">
                                        @if ( $searchParams['group_by'] == "none" )
                                            @if ( $analytic->rate == 1 )
                                                <span class="badge bg-success">Like</span>
                                            @else 
                                                <span class="badge bg-danger">Dislike</span>
                                            @endif
                                        @else 
                                            <span class="badge bg-info">{{$analytic->rate}}</span>
                                        @endif
                                    </td>

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

            <p class="pt-4 px-2 ">&nbsp;&nbsp;<i class="text-warning">Atenção</i>: se você utilizar o 'group by' a coluna 'rate' será um inteiro que confiz com quantas vezes a resposta foi avaliada</p>
                
          </div>

        </div><!-- Coluna da direita -->

      </div>
    </section>

  </main>