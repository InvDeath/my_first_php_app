<?php
    class ViewBookitAdmin extends View
    {
        protected function askMainContent()
        {
            return '{%TPL_FORMBOOKIT%}';
        }

        /*GEN methods*/
        protected function processGenBookitCurroom()
        {
            return 'Boardroom '.$this->genData['main_content']['curroom'];
        }

        protected function processGenBookitEmployees()
        {
            $rowTplInner = $this->loadTplRowFile('GEN_BOOKIT_BOOKITEMPLOYEE');
            $retContent = '';
            foreach ($this->genData['main_content']['employees'] as $id => $emp)
            {
                $retContent .= $this->genRow('ID', $id, $rowTplInner);
                $retContent = $this->genRow('NAME', $emp['name'], $retContent);
            }
            
            return $retContent;
        }

        protected function processGenBookitMonths()
        {
            $rowTplInner = $this->loadTplRowFile('GEN_BOOKIT_BOOKITEMPLOYEE');
            $retContent = '';
            foreach ($this->genData['main_content']['dates']['months'] as $id => $name)
            {
                $retContent .= $this->genRow('ID', $id, $rowTplInner);
                $retContent = $this->genRow('NAME', $name, $retContent);
            }

            return $retContent;
        }

        protected function processGenBookitDays()
        {
            $rowTplInner = $this->loadTplRowFile('GEN_BOOKIT_BOOKITEMPLOYEE');
            $retContent = '';
            foreach ($this->genData['main_content']['dates']['days'] as $id => $name)
            {
                $retContent .= $this->genRow('ID', $id, $rowTplInner);
                $retContent = $this->genRow('NAME', $name, $retContent);
            }
            
            return $retContent;
        }
        
        protected function processGenBookitYears()
        {
            $rowTplInner = $this->loadTplRowFile('GEN_BOOKIT_BOOKITEMPLOYEE');
            $retContent = '';
            foreach ($this->genData['main_content']['dates']['years'] as $id => $name)
            {
                $retContent .= $this->genRow('ID', $name, $rowTplInner);
                $retContent = $this->genRow('NAME', $name, $retContent);
            }
            
            return $retContent;
        }

        protected function processGenBookitTime()
        {
            if(USR_TIME == 24)
            {
                return '{%TPL_TIMETF%}';
            }
            elseif(USR_TIME == 12)
            {
                return '{%TPL_TIMEAMPM%}';
            }
            else
            {
                throw new Exception('Wrong time format in config.');
            }
        }

        protected function processGenBookitHours()
        {
            $rowTplInner = $this->loadTplRowFile('GEN_BOOKIT_BOOKITEMPLOYEE');
            $retContent = '';
            foreach ($this->genData['main_content']['dates']['hours'] as $id => $name)
            {
                $retContent .= $this->genRow('ID', $name, $rowTplInner);
                $retContent = $this->genRow('NAME', $name, $retContent);
            }

            return $retContent;

        }
        

        protected function processGenBookitMinutes()
        {
            $rowTplInner = $this->loadTplRowFile('GEN_BOOKIT_BOOKITEMPLOYEE');
            $retContent = '';
            foreach ($this->genData['main_content']['dates']['minutes'] as $id => $name)
            {
                $retContent .= $this->genRow('ID', $name, $rowTplInner);
                $retContent = $this->genRow('NAME', $name, $retContent);
            }

            return $retContent;

        }

        protected function addingResult()
        {
            return $this->genData['messages'];
        }         
        

    }
?>
