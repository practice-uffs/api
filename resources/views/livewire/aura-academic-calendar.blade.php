<div class="page">
    <link rel="stylesheet" href="{{ asset('css/aura-calendar.css')}}">

    <div class="popup-header">
        <button class="close-btn" wire:click="closePopup">X</button>
    </div>
    <h1 class="title">Calendário Acadêmico</h1>

    <label class="select_label" for="campus">Selecione o campus:</label>
    <select class="select_campus" id="campus">
        <option value="chapeco" selected>Chapecó</option>
        <option value="laranjeiras_do_sul">Laranjeiras do Sul</option>
        <option value="erechim">Erechim</option>
        <option value="cerro_largo">Cerro Largo</option>
        <option value="realeza">Realeza</option>
        <option value="passo_fundo">Passo Fundo</option>
    </select>

    <div class="calendar">
        <div class="calendar-header">
            <div class="calendar-info">{{ $months[$calendar['month']] }}/{{ $year }}</div>
            <div>
                <button class="change-month change-month--prev" wire:click="changeMonth('prev')"><i class="fas fa-angle-left"></i></button>
                <button class="change-month change-month--next" wire:click="changeMonth('next')"><i class="fas fa-angle-right"></i></button>
            </div>
        </div>

        <div class="calendar-week-days">
            <div>Dom</div>
            <div>Seg</div>
            <div>Ter</div>
            <div>Qua</div>
            <div>Qui</div>
            <div>Sex</div>
            <div>Sáb</div>
        </div>

        <div class="calendar-days">
            @foreach($calendar['array'] as $week)
                <div class="week">
                    @foreach($week as $day)
                        <div @class([
                            'day',
                            'day--not-weekend' => strlen($day[0]) != 0,
                            'day--weekend' => strlen($day[0]) != 0 && $day[1] >= 6
                        ])>
                            <span>{{ $day[0] }}</span>
                        </div>
                    @endforeach
                </div>
            @endforeach
        </div>
    </div>
</div>
