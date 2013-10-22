<?php
class ModelEventAdmin extends Model
{
    protected function askMainContent()
    {
        $ret['dates'] = $this->askDates();
        $ret['employees'] = $this->askEmployees();
        $ret['event'] = $this->dbSelectEventById($this->GET['id']);

        return $ret; 
    }

    protected function showEvent()
    {
        return $this->askMainContent();
    }

    private function askEvent($id)
    {
        return $this->dbSelectEventById($id);
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

    protected function editEvent()
    {
        if(isset($this->POST['remove']))
        {
            return $this->removeEvent();
        }  

        if(isset($this->POST['update']))
        {
            return $this->updateEvent();
        }
    }

    private function removeEvent()
    {
        if(isset($this->POST['reccuring']))
        {
            $this->dbRemoveEventsReq($this->POST['reccuring']);
            $this->dbRemoveRecursions($this->POST['reccuring']);
            
            $this->messages = 'Events were deleted';
            $this->viewMethod = 'processGenMessage'; 
            return FALSE;    
        }
        
        $this->dbRemoveEventById($this->GET['id']);

        $this->messages = 'Event was deleted';
        $this->viewMethod = 'processGenMessage'; 
        
        return FALSE;
    }

    private function updateEvent()
    {
        $id = $this->GET['id'];
        if(USR_TIME == 24)
        {
            if(!preg_match('/^[0-2][0-9]:[0-5][0-9]$/', $this->POST['text_start']))
            {
                $this->messages = 'Wrong time';
                $this->viewMethod = 'processGenMessage';

                return FALSE;
            }

            if(!preg_match('/^[0-2][0-9]:[0-5][0-9]$/', $this->POST['text_finish']))
            {
                $this->messages = 'Wrong time';
                $this->viewMethod = 'processGenMessage';
    
                return FALSE;
            } 
        }
        
        if(USR_TIME == 12)
        {
            if(!preg_match('/^[0-1][0-9]:[0-5][0-9]$/', $this->POST['text_start']))
            {
                $this->messages = 'Wrong time';
                $this->viewMethod = 'processGenMessage';

                return FALSE;
            }

            if(!preg_match('/^[0-1][0-9]:[0-5][0-9]$/', $this->POST['text_finish']))
            {
                $this->messages = 'Wrong time';
                $this->viewMethod = 'processGenMessage';
    
                return FALSE;
            } 
        }

        $textStart = explode(':', $this->POST['text_start']); 
        $textFinish = explode(':', $this->POST['text_finish']);
        
        if(USR_TIME == 12)
        {
            if($this->POST['s_ampm'] == 'pm')
            {
                $textStart[0] = $textStart[0]+12;
            }

            if($this->POST['f_ampm'] == 'pm')
            {
                $textFinish[0] = $textFinish[0]+12;
            }
        }

        if(isset($this->POST['reccuring']))
        {
            $id = $this->POST['reccuring'];
            $events = $this->dbSelectEventByReqId($id);
           
            foreach ($events as $event)
            {
                $timeStart = date('Y-m-d H:i:s', mktime($textStart[0], $textStart[1],0,date('m',strtotime($event['start'])),date('d', strtotime($event['start'])),date('Y', strtotime($event['start']))));  
                $timeFinish = date('Y-m-d H:i:s', mktime($textFinish[0], $textFinish[1],0,date('m',strtotime($event['start'])),date('d', strtotime($event['start'])),date('Y', strtotime($event['start']))));  
                
                if(strtotime($timeStart)>=strtotime($timeFinish))
                {
                    $this->messages = 'Wrong dates';
                    $this->viewMethod = 'processGenMessage';

                    return FALSE;
                }

                if($this->dbAskIntersections($timeStart, $timeFinish, $_SESSION['room'], $event['id']))
                {
                    $this->messages = 'Room is busy';
                    $this->viewMethod = 'processGenMessage';

                    return FALSE;
                }

                $this->dbUpdateEvent($event['id'], $timeStart, $timeFinish, $this->POST['employee'], $this->POST['notes']);
            }
            
            $this->messages = 'Events were updated';
            $this->viewMethod = 'processGenMessage';

            return FALSE;
        }

        $timeStart = date('Y-m-d H:i:s', mktime($textStart[0], $textStart[1],0,date('m',strtotime($this->POST['ymd'])),date('d', strtotime($this->POST['ymd'])),date('Y', strtotime($this->POST['ymd']))));  
        $timeFinish = date('Y-m-d H:i:s', mktime($textFinish[0], $textFinish[1],0,date('m',strtotime($this->POST['ymd'])),date('d', strtotime($this->POST['ymd'])),date('Y', strtotime($this->POST['ymd']))));  
        
        if(strtotime($timeStart)>=strtotime($timeFinish))
        {
            $this->messages = 'Wrong dates';
            $this->viewMethod = 'processGenMessage';

            return FALSE;
        }

        if($this->dbAskIntersections($timeStart, $timeFinish, $_SESSION['room'], $id))
        {
            $this->messages = 'Room is busy';
            $this->viewMethod = 'processGenMessage';
            
            return FALSE;
        }
        
        $this->dbUpdateEvent($id, $timeStart, $timeFinish, $this->POST['employee'], $this->POST['notes']);

        $this->messages = 'Events were updated';
        $this->viewMethod = 'processGenMessage';

        return FALSE;
    }


}
?>
