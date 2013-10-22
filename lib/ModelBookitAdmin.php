<?php

class ModelBookitAdmin extends Model
{
    protected function askMainContent()
    {
        $ret['curroom'] = $_SESSION['room'];
        $ret['employees'] = $this->askEmployees();
        $ret['dates'] = $this->askDates();
        
        return $ret;    
    }

    private function askEmployees()
    {
        return $this->pullEmployees(); 
    }

    private function askDates()
    {
        $dates = array();

        //years
        $dates['years'][0] = date('o');
        for($i = 1;$i<=10;$i++)
        {
            $dates['years'][$i] = $dates['years'][$i - 1] + 1;
        }

        //months
        for($i=1;$i<=12;$i++)
        {
            $dates['months'][$i] = date('F',mktime(0,0,0,$i));
        }

        //days
        for($i = 1;$i<=31;$i++)
        {
            $dates['days'][$i] = $i;
        }
        
        //hours
        if(USR_TIME == 12)
        {
            for($i=1;$i<=12;$i++)
            {
                $dates['hours'][$i] = $i; 
            } 
        }
        elseif(USR_TIME == 24)
        {
            for($i=0;$i<24;$i++)
            {
                $dates['hours'][$i] = $i;
            }
        }
        else
        {
            throw new Exception ('Wrong time type.');
        }

        //minutes
        for($i=0;$i<60;$i=$i+USR_MINUTES_STEP)
        {
            $dates['minutes'][$i] = $i;
        }
        $dates['minutes'][0] = '00';

        return $dates;
        
    }

    protected function addEvent()
    {
        $this->viewMethod = 'addingResult';

        if(empty($this->POST))
        {
            $this->messages = '{%LNG_NODATA%}';
            return FALSE;
        }
        

        $data = $this->POST;

        $start = $data['year'].'-'.$data['month'].'-'.$data['day'].' '.$data['from_hours'].':'.$data['from_minutes'].':00';
        $finish = $data['year'].'-'.$data['month'].'-'.$data['day'].' '.$data['to_hours'].':'.$data['to_minutes'].':00';
        
        if(USR_TIME == 12)
        {
            if($this->POST['s_ampm'] == 'pm')
            {
                $start = $data['year'].'-'.$data['month'].'-'.$data['day'].' '.($data['from_hours']+12).':'.$data['from_minutes'].':00';
            }

            if($this->POST['f_ampm'] == 'pm')
            {
                $finish = $data['year'].'-'.$data['month'].'-'.$data['day'].' '.($data['to_hours']+12).':'.$data['to_minutes'].':00';
            }
        }

        $mysqlTimeStart = date( 'Y-m-d H:i:s', strtotime($start));
        $mysqlTimeFinish =  date( 'Y-m-d H:i:s', strtotime($finish));
        
        if(strtotime($mysqlTimeStart)>=strtotime($mysqlTimeFinish))
        {
            $this->messages = '{%LNG_WRONG_DATES%}';
            return FALSE;
        }

        $room = $_SESSION['room'];
        $description = $data['description'];
        $employee = $data['employee'];

        if($data['reccuring'] == 'yes')
        {
            if($data['reccuring_value']>4)
            {
                $this->messages = '{%LNG_ONLY_4%}';
                return FALSE;
            }
            
            $this->addEventRec($data, $employee, $mysqlTimeStart, $mysqlTimeFinish, $room, $description);
            $this->messages = 'Added some evenrs';
            return FALSE;
        }

        if($this->dbAskIntersections($mysqlTimeStart, $mysqlTimeFinish, $room))
        {
            $this->messages = '{%LNG_BUSY%}';
            return FALSE;
        }

        $this->dbAddEvent($employee, $mysqlTimeStart, $mysqlTimeFinish, $room, $description);

        $this->messages = '{%LNG_EVENT_ADDED%}';
        return FALSE;
    }

    private function addEventRec($data, $employee, $mysqlTimeStart, $mysqlTimeFinish, $room, $description)
    {
        switch($data['recurring_type'])
        {
            case "weekly":
                $counter = 7;
                break;        
            case "bi_weekly":
                $counter = 14;
                break;
            case "monthly":
                $counter = 30;
                break;
            default:
                break;
        }
        $reqId = $this->dbAddReccuring();
        
        $start = strtotime($mysqlTimeStart);
        $finish = strtotime($mysqlTimeFinish);

        for($i=0;$i<$data['reccuring_value'];$i++)
        {
            $testStart = $mysqlTimeStart;
            $testFinish = $mysqlTimeFinish;

            if($this->dbAskIntersections($testStart, $testFinish, $room))
            {
                $this->messages = '{%LNG_BUSY%}';
                return FALSE;
            }

            $testStart = $testStart+$counter*86400; 
            $testFinish = $testFinish+$counter*86400;   
        }

        $this->dbAddEvent($employee, $mysqlTimeStart, $mysqlTimeFinish, $room, $description, $reqId);

        for($i=1;$i<$data['reccuring_value'];$i++)
        {
            $start = $start+$counter*86400;
            $finish = $finish+$counter*86400;
            
            $this->dbAddEvent($employee, date( 'Y-m-d H:i:s', $start), date( 'Y-m-d H:i:s', $finish), $room, $description, $reqId);
                
        }
    }
}
?>
