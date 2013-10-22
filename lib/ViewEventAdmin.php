<?php
class ViewEventAdmin extends View
{
    protected function askMainContent()
    {
        return '{%TPL_EVENTPAGE%}';
    }

    protected function processGenEventId()
    {
        return $this->genData['main_content']['event']['id'];
    } 

    protected function processGenEventTime()
    {
        if(USR_TIME == 24)
        {
            $retContent = $this->loadTplRowFile('GEN_EVENT_TIME');
            $textStart = date('H:i', strtotime($this->genData['main_content']['event']['start']));
            $textFinish = date('H:i', strtotime($this->genData['main_content']['event']['finish']));
        }

        if(USR_TIME == 12)
        {
            $retContent = $this->loadTplRowFile('GEN_EVENT_TIMEAMPM');
            $textStart = date('h:i', strtotime($this->genData['main_content']['event']['start']));
            $textFinish = date('h:i', strtotime($this->genData['main_content']['event']['finish']));
            $toggleStart = date('A', strtotime($this->genData['main_content']['event']['start']));
            $toggleFinish = date('A', strtotime($this->genData['main_content']['event']['finish']));

            if($toggleStart == 'AM')
            {
                $retContent = $this->genRow('S_A', 'selected', $retContent);
                $retContent = $this->genRow('S_P', '', $retContent);
            }
            else
            {
                $retContent = $this->genRow('S_P', 'selected', $retContent);
                $retContent = $this->genRow('S_A', '', $retContent);
            }
            
            if($toggleFinish == 'AM')
            {
                $retContent = $this->genRow('F_A', 'selected', $retContent);
                $retContent = $this->genRow('F_P', '', $retContent);
            }
            else
            {
                $retContent = $this->genRow('F_P', 'selected', $retContent);
                $retContent = $this->genRow('F_A', '', $retContent);
            }
        }
        $ymd = date('Y-m-d', strtotime($this->genData['main_content']['event']['start']));
        $retContent = $this->genRow('START_TIME', $textStart, $retContent);
        $retContent = $this->genRow('FINISH_TIME', $textFinish, $retContent);
        $retContent = $this->genRow('YMD', $ymd, $retContent);

        return $retContent;
    }

    protected function processGenEventNotes()
    {
        $rowTplInner = $this->loadTplRowFile('GEN_EVENT_NOTES');
        $retContent = $this->genRow('NOTES', $this->genData['main_content']['event']['desc'],$rowTplInner);

        return $retContent;
    }

    protected function processGenEventWho()
    {
        $rowTplInner = $this->loadTplRowFile('GEN_EVENT_WHO');
        $employeeId = $this->genData['main_content']['event']['employee'];
        $retContent = '';
        foreach($this->genData['main_content']['employees'] as $id => $emp)
        {
            $selected = '';
            if($id == $employeeId)
            {
                $selected = 'selected';
            }
            $retContent .= $this->genRow('ID', $id, $rowTplInner);
            $retContent = $this->genRow('NAME', $emp['name'], $retContent);
            $retContent = $this->genRow('SELECTED', $selected, $retContent);
        }

        return $retContent; 
    }

    protected function processGenEventSubmitted()
    {
        $rowTplInner = $this->loadTplRowFile('GEN_EVENT_SUBMITTED');
        if(USR_TIME == 24)
        {
            $date = $this->genData['main_content']['event']['added'];
        }
        if(USR_TIME == 12)
        {
            $date = date('Y-d-m g:i:s A', strtotime($this->genData['main_content']['event']['added']));
        }
        $retContent = $this->genRow('SUBMITTED', $date, $rowTplInner);

        return $retContent;
    }

    protected function processGenEventReccuring()
    {
        if($this->genData['main_content']['event']['req_id'] == 0)
        {
            return '';
        }

        $rowTplInner = $this->loadTplRowFile('GEN_EVENT_RECCURING');
        $retContent = $this->genRow('REQ_ID', $this->genData['main_content']['event']['req_id'], $rowTplInner);

        return $retContent;
    }
}
?>
