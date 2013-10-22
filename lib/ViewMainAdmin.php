<?php

class ViewMainAdmin extends View
{
    protected function askMainContent()
    {
        return '{%TPL_ADMINPAGE%}';
    }

    protected function processGenRoomslinks()
    {
        $rowTplInner = $this->loadTplRowFile('GEN_MAIN_ROOMS');
        $value = $this->makeHtmlCouple($this->genData['main_content']['rooms'], 'GEN_MAIN_ROOMURL');
        $retContent = $this->genRow('LINKS', $value, $rowTplInner);
        
        return $retContent;
    }

    protected function processGenCurroom()
    {
        $rowTplInner = $this->loadTplRowFile('GEN_MAIN_CURROOM');
        $retContent = $this->genRow('CURROOM', $this->genData['main_content']['curroom'], $rowTplInner);
        
        return $retContent;
    }

    protected function processGenNamesdays()
    {
        $tplTr = $this->loadTplRowFile('GEN_MAIN_TRCALLNAMES'); 
        $tplTd = $this->loadTplRowFile('GEN_MAIN_TDCALLNAMES');

        $retTd = '';
        foreach($this->genData['main_content']['calendar']['days'] as $day)
        {
            $retTd .= $this->genRow('DAY', $day, $tplTd);
        }
        return $this->genRow('ROW', $retTd, $tplTr);

    }

    protected function processGenCalendar()
    {
        $weeksCount = count($this->genData['main_content']['calendar'])-2;
        $weeksTable = '{%GEN_NAMESDAYS%}';
        for($i=0;$i<$weeksCount;$i++)
        {
            //foreach($this->genData['main_content']['calendar'] as $arrWeek)
            $arrWeek = $this->genData['main_content']['calendar'][$i];
        
            $days = '';
            foreach($arrWeek as $arrDay)
            {
                $eventLinks = '';
                $day='';
                $rowTplDay = $this->loadTplRowFile('GEN_MAIN_TDCALL');
                    
                if(!empty($arrDay))
                {
                    $retDay = $this->genRow('DAY',$arrDay['day'], $rowTplDay);
                    if(isset($arrDay['events']))
                    {
                        foreach($arrDay['events'] as $event)
                        {
                            $arrForCouple = array($event['id'] => $event['href']);
                            $eventLinks .= $this->makeHtmlCouple($arrForCouple, 'GEN_MAIN_CALLEVENTURL');
                        }
                        $retDay = $this->genRow('EVENTS', $eventLinks, $retDay);
                    }
                    else
                    {
                        $retDay = $this->genRow('EVENTS', $eventLinks, $retDay);
                    }
                }
                else
                {
                    $retDay = $this->genRow('DAY',$day,$rowTplDay);
                    $retDay = $this->genRow('EVENTS',$eventLinks,$retDay);
                }
                $days .= $retDay;
            }
            
            $rowTplWeek = $this->loadTplRowFile('GEN_MAIN_TRCALL');
            $weeksTable .= $this->genRow('WEEK', $days, $rowTplWeek); 
        } 

        return $weeksTable;
    }

    protected function processGenMainCallinks()
    {
        $rowTplInner = $this->loadTplRowFile('GEN_MAIN_CALLINKS');
        $retContent = $this->genRow('LEFT', $this->genData['main_content']['calendar']['links']['left'], $rowTplInner);
        $retContent = $this->genRow('MONTH', $this->genData['main_content']['calendar']['links']['month'], $retContent);
        $retContent = $this->genRow('YEAR', $this->genData['main_content']['calendar']['links']['year'], $retContent);
        $retContent = $this->genRow('RIGHT', $this->genData['main_content']['calendar']['links']['right'], $retContent);
        
        return $retContent;
    }

}
?>
