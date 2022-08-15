<?php

namespace App\Services;
use App\Models\AcademicCalendar;

class AcademicCalendarService
{
    protected $months;
    protected $campus;

    public function __construct() {
        $this->months = [
            'Janeiro',
            'Fevereiro',
            'Março',
            'Abril',
            'Maio',
            'Junho',
            'Julho',
            'Agosto',
            'Setembro',
            'Outubro',
            'Novembro',
            'Dezembro'
        ];

        $this->campus = [
            'chapeco' => 'Chapecó',
            'laranjeiras_do_sul' => 'Laranjeiras do Sul',
            'erechim' => 'Erechim',
            'cerro_largo' => 'Cerro Largo',
            'realeza' => 'Realeza',
            'passo_fundo' => 'Passo Fundo'
        ];
    }

    public function getCalendars($campus = null) {
        if ($campus != null && !isset($this->campus[$campus])){
            return [];
        }

        if ($campus == null) {
            return AcademicCalendar::all();
        }

        $calendars = AcademicCalendar::where('title', 'LIKE', '%' . $this->campus[$campus] . '%')->get();

        return $calendars;
    }

    public function getCurrentMonthCalendar($campus = null) {
        $currentMonth = $this->months[(int) date('n') - 1];
        $currentYear = (int) date('Y');

        $currentMonthCalendar = $this->getCalendarEventsByMonth($currentMonth, $currentYear, $campus);

        return $currentMonthCalendar;
    }

    public function getCalendarEventsByMonth($month, $year, $campus = null) {
        $calendars = $this->getCalendars($campus);
        $month = $this->months[$month];

        $currentMonthEvents = [];
        foreach ($calendars as $calendar) {
            foreach ($calendar['data'] as $key => $calendarMonth) {
                $splittedKey = explode('/', $key);
       
                if (strcasecmp(trim($splittedKey[0]), $month) == 0 && (int) $splittedKey[1] == $year) {
                    array_push($currentMonthEvents,[
                        'title' => $calendar['title'],
                        'url' => $calendar['url'],
                        'date' => $key,
                        'events' => $calendarMonth['events'],
                        'festivities' => $calendarMonth['festivities']
                    ]);
                }
            }
        }
        return $currentMonthEvents;
    }

    public function getCurrentDateEvents($campus = null) {
        $currentDay = date('y-m-d');
        return $this->getCalendarEventsByDate($currentDay, $campus);
    }

    // Recebe uma data no formato y-m-d
    public function getCalendarEventsByDate($date, $campus = null) {
        $dateCalendar = [];
        $date = strtotime($date);

        $month = (int) date('n', $date) - 1;
        $year = date('Y', $date);
        $monthCalendars = $this->getCalendarEventsByMonth($month, $year, $campus);

        if (isset($monthCalendars)) {
            foreach ($monthCalendars as $monthCalendar) {
                $dateEvents = [];
                $dateFestivities = [];
                foreach ($monthCalendar['events'] as $event) {
                    if (str_contains($event['period'], 'e') || str_contains($event['period'], 'a')) {
                        $splittedPeriod = preg_split('/ e | a /', $event['period']);
        
                        $days = explode(',', $splittedPeriod[0]); // Vai ser usado caso o período seja no estilo x, y e z
                        $firstDay = explode('/', $days[0]);
                        $secondDay = explode('/', $splittedPeriod[1]);
        
                        // Caso o período esteja no formato dd/mm pegamos o mês a partir dele
                        // caso contrário pegamos o mês da data recebida na chamada da função
                        $firstMonth= isset($firstDay[1]) ? $firstDay[1] : date('n', $date);
                        $secondMonth = isset($secondDay[1]) ? $secondDay[1] : date('n', $date);
        
                        $firstDateTimestamp = strtotime(date('Y') . '-' . $firstMonth. '-' . $firstDay[0]);
                        $secondDateTimestamp = strtotime(date('Y') . '-' . $secondMonth . '-' . $secondDay[0]);
        
                        if (str_contains($event['period'], 'e')) {
                            if(count($days) > 1) { // Se o período for no estilo x, y e z 
                                foreach ($days as $day) {
                                    $splittedDay = explode('/', $day);

                                    $dayTimestamp = strtotime((int)date('Y') . '-' . (isset($splittedDay[1]) ? $splittedDay[1] : date('n', $date)) . '-' . trim($splittedDay[0]));
                                    if ($dayTimestamp == $date) {
                                        array_push($dateEvents, $event);
                                        break;
                                    }
                                }
                                if ($secondDateTimestamp == $date) {
                                    array_push($dateEvents, $event);
                                }
                            } else if ($firstDateTimestamp == $date || $secondDateTimestamp == $date) {
                                array_push($dateEvents, $event);
                            }
                        } else if (str_contains($event['period'], 'a')) {
                            if ($date >= $firstDateTimestamp && $date <= $secondDateTimestamp) {
                                array_push($dateEvents, $event);
                            }
                        }
                    } else {
                        $eventDate = explode('/', $event['period']);
                        $eventMonth = isset($eventDate[1]) ? $eventDate[1] : date('n', $date);
    
                        $eventTimestamp = strtotime(date('Y') . '-' . $eventMonth . '-' . $eventDate[0]);
                        if ($eventTimestamp == $date) {
                            array_push($dateEvents, $event);
                        }
                    }
                }
    
                foreach ($monthCalendar['festivities'] as $festivity) {
                    list($day, $event) = explode(' – ', $festivity, 2);
                    if ((int) date('j', $date) == (int) $day) {
                        if (str_starts_with($event, ' – ')) {
                            $event = str_replace(' – ', '', $event);
                        }
                        array_push($dateFestivities, $event);
                    }
                }

                array_push($dateCalendar, [
                    'title' => $monthCalendar['title'],
                    'url' => $monthCalendar['url'],
                    'date' => date('d/m/Y', $date),
                    'events' => $dateEvents,
                    'festivities' => $dateFestivities
                ]);
            }
        }

        return $dateCalendar;
    }
}
