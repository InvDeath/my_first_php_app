<?php
class ViewEmplistAdmin extends View
{
    protected function askMainContent()
    {
        return '{%TPL_EMPLOYEES%}';
    }   

    protected function showAddForm()
    {
        return '{%TPL_FORMADDEMPL%}';
    }

    protected function showEditForm()
    {
        return '{%GEN_FORMEDITEMPL%}';
    }

    protected function confirmRemoving()
    {
        return '{%TPL_REMEMP%}';
    } 

    protected function processGenEmplist()
    {
        if (empty($this->genData['main_content']))
        {
            return 'Empty list!';
        }

        $rowTplInner = $this->loadTplRowFile('GEN_EMPLIST_EMPLIST');
        $retContent = '';
        foreach ($this->genData['main_content'] as $id => $emp)
        {
            $retContent .= $this->genRow('NAME', $emp['name'], $rowTplInner);
            $retContent = $this->genRow('EMAIL', $emp['email'], $retContent);
            $retContent = $this->genRow('ID', $id, $retContent);
        }

        
        return $retContent;    
    }

    protected function processGenFormeditempl()
    {
        $rowTplInner = $this->loadTplRowFile('GEN_EMPLIST_FORMEDITEMPL');

        $retContent = $this->genRow('NAME', $this->genData['main_content']['name'], $rowTplInner);
        $retContent = $this->genRow('EMAIL', $this->genData['main_content']['email'], $retContent);

        return $retContent;
    }
 
}
?>
