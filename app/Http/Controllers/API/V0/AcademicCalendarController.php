<?php

namespace App\Http\Controllers\API\V0;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use \CCUFFS\Scrap\AcademicCalendarUFFS;

class AcademicCalendarController extends Controller
{
    protected AcademicCalendarUFFS $academicCalendar;
    protected $months;

    public function __construct() {
        $this->academicCalendar = new \CCUFFS\Scrap\AcademicCalendarUFFS();
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
    }

    public function getCalendars() {
        $calendar = $this->academicCalendar->fetchCalendars();

        return $calendar;
    }

    public function getCurrentMonthCalendar() {
        $currentMonth = $this->months[(int) date('n') - 1];
        $currentYear = (int) date('Y');
        $calendar = $this->getCalendars();

        $currentMonthCalendar = $this->getCalendarEventsByMonth($calendar, $currentMonth, $currentYear);

        return $currentMonthCalendar;
    }

    public function getCalendarEventsByMonth($calendars, $month, $year) {
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

    public function getCurrentDateEvents() {
        $currentDay = date('y-m-d');
        return $this->getCalendarEventsByDate($currentDay);
    }

    // Recebe uma data no formato y-m-d
    public function getCalendarEventsByDate($date) {
        $dateCalendar = [];
        $date = strtotime($date);

        $calendar = $this->getCalendars();
        $month = $this->months[(int) date('n', $date) - 1];
        $year = date('Y', $date);
        $monthCalendars = $this->getCalendarEventsByMonth($calendar, $month, $year);

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
