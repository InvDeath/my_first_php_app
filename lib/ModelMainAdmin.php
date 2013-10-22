<?php

class ModelMainAdmin extends Model
{
    private $dateString;

    public function __construct($get, $post)
    {
        parent::__construct($get, $post);

        $this->dateString = 0;
    }

    protected function askMainContent()
    {
        
        $ret['rooms'] = $this->askLinksRooms();
        $ret['curroom'] = $this->askCurrentRoom();
        $ret['calendar'] = $this->buildCelendar($this->dateString);
        return $ret;
    }

    protected function setDateString()
    {
        $this->dateString = $this->GET['id'];
        return $this->askMainContent();
    }

    private function buildCelendar($dateStr)
    {
        if($dateStr==0)
        {
            $year = date('Y');
            $month = date('m');
        }
        else
        {
            $curData = explode('-',$dateStr);
            $year = $curData[0];
            $month = $curData[1];
        }
        

        $linkYear = $year;
        $linkMonth = date('F',mktime(0,0,0, $month, 1, $year));
        $linkLeft = $year.'-'.($month-1);
        $linkRight = $year.'-'.($month+1);
        if ((int)$month == 1)
        {
            $linkLeft = ($year-1).'-12';
        }
        if ((int)$month == 12)
        {
            $linkRight = ($year+1).'-1';
        }

        
        if($events = $this->dbSelectEventsForMonth($year, $month, $_SESSION['room']))
        {
            $iv = 0;
            foreach ($events as $id => $event)
            {
                $arrEvents[$iv]['id'] = $id;
                $arrEvents[$iv]['href'] = date('G:i', strtotime($event['start'])).' - '.date('G:i', strtotime($event['finish']));
                if(USR_TIME == 12)
                {
                    $arrEvents[$iv]['href'] = date('h:i a', strtotime($event['start'])).' - '.date('h:i a', strtotime($event['finish']));
                }
                $arrEvents[$iv]['day'] = date('j', strtotime($event['start'])); 

                $iv++;
            }
        }
        $dayOfMonth = date('t', mktime(0, 0, 0, $month, date('d'), $year) ); 
        $dayCount = 1;
        
        //first week
        $num = 0;
        for($i = 0; $i < 7; $i++)
        {
            $dayOfWeek = date('w', mktime(0, 0, 0, $month, $dayCount, $year));
            $dayOfWeek = $dayOfWeek - USR_FIRSTDAY;
        
            if($dayOfWeek == -1) 
            {
                $dayOfWeek = 6;
            }
        
            if($dayOfWeek == $i)
            {
                if(!empty($arrEvents))
                {
                    foreach($arrEvents as $ev)
                    {
                        if($ev['day'] == $dayCount)
                        {
                            $week[$num][$i]['events'][] = $ev;
                        }
                    }
                }
                
                $week[$num][$i]['day'] = $dayCount;
                $dayCount++;
            }
            else
            {
                $week[$num][$i] = "";
            }
        }

        while(true)
        { 
            $num++;
            for($i = 0; $i < 7; $i++)
            {
                if(!empty($arrEvents))
                {
                    foreach($arrEvents as $ev)
                    {
                        if($ev['day'] == $dayCount)
                        {
                            $week[$num][$i]['events'][] = $ev;
                        }
                    }
                }
                
                $week[$num][$i]['day'] = $dayCount;

                $dayCount++;
                if($dayCount > $dayOfMonth) 
                {
                    break;
                }
            }
            if($dayCount > $dayOfMonth) 
            {
                break;
            }
        }
        
        $week['links']['year'] = $linkYear;
        $week['links']['month'] = $linkMonth;
        $week['links']['left'] = $linkLeft;
        $week['links']['right'] = $linkRight;
        
        if(USR_FIRSTDAY == 0)
        {
            $week['days'] = array(
                '{%LNG_SUNDAY%}',
                '{%LNG_MONDAY%}',
                '{%LNG_TUESDAY%}',
                '{%LNG_WEDNESDAY%}',
                '{%LNG_THURSDAY%}',
                '{%LNG_FRIDAY%}',
                '{%LNG_SATURDAY%}'
            );
        }
        else
        {
            $week['days'] = array(
                '{%LNG_MONDAY%}',
                '{%LNG_TUESDAY%}',
                '{%LNG_WEDNESDAY%}',
                '{%LNG_THURSDAY%}',
                '{%LNG_FRIDAY%}',
                '{%LNG_SATURDAY%}',
                '{%LNG_SUNDAY%}'
            );
        }

        return $week;

    }

    private function askLinksRooms()
    {
        for($i=1;$i<=USR_ROOMS;$i++)
        {
            $ret[$i] = 'Boardroom '.$i;
        }
        return $ret; 
    }

    protected function changeRoom()
    {
        $_SESSION['room'] = $this->GET['id'];
        return $this->askMainContent();
    }
    
    private function askCurrentRoom()
    {
        return $_SESSION['room'];
    }    
}
?>
