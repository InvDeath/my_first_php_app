<?php
/*
 * lib/View.php
 */

 class View 
 {
     
     /**
     * innerIndex 
     * 
     * Conteins static text for generating page;
     *
     * @var string
     */
    protected $innerIndex;
   
    /**
     * genData 
     * 
     * All data for generating, replacing and for others attions here;
     *
     * @var aray
     */
    protected $genData;
   
    /**
     * langArr 
     *
     * Array with 'mark'=>'string' for replacing.
     * 
     * @var array
     */
	protected $langArr;

	
	function __construct()
	{
			$this->innerIndex = file_get_contents(DIR_TEMPLATES.'/'.'index.tpl.php');
			$this->langArr = $this->loadLngFiles();
	}

    /**
     * getData 
     * 
     * Returns finished page.
     *
     * @param array $data 
     * @return string
     */
	public function getData (array $data)
	{	
        $this->genData = $data;
        //Deb::p($this->genData);
        return $this->razor($this->innerIndex);
	}

    /**
     * parseMarks 
     *
     * Returns all marks for replacing from input text.
     *
     * @param string $inner 
     * @return array
     */
	protected function parseMarks($inner)
	{
		preg_match_all("/\{%(.*)\%}/", $inner, $marks);

		foreach($marks['1'] as $longMark)
        {
            preg_match_all("/^([A-Z]*)_.*/", $longMark, $mark);
        
		switch ($mark['1']['0'])
			{
				case 'LNG':
					$sepMarks['STR'][] = $longMark;
					break;
				case "TPL":
					$sepMarks['TPL'][] = $longMark;
                    break;
                case "GEN":
                    $sepMarks['GEN'][] = $longMark;
                    break;    
				default:
					//$sepMarks['OTH'][] = $longMark;
					break;
			}
        }
        if(isset($sepMarks))
        {
            return $sepMarks;
        }
	}
	
    /**
     * razor 
     * 
     * Replacing all marks while they are in text. 
     *
     * @param string $innerHtml 
     * @return string
     */
	protected function razor($innerHtml)
	{
        while($marks = $this->parseMarks($innerHtml))
        {
            if(isset($marks['TPL']))
            {
                $innerHtml = $this->processTpl($marks['TPL'], $innerHtml);
            } 

            if(isset($marks['STR']))
            {
                $innerHtml = $this->processStr($marks['STR'], $innerHtml);
            }

            if(isset($marks['GEN']))
            {
                $innerHtml = $this->processGen($marks['GEN'], $innerHtml);
            }
            
            //others types
        }	
        
        return $innerHtml;
	}

    /**
     * fillLngMarks 
     * 
     * Returns array with text values for replacing.
     *
     * @param array $emptyLngMarks 
     * @return array
     */
	protected function fillLngMarks ($emptyLngMarks)
	{
		foreach ($emptyLngMarks as $mark)
		{
			$fullLngMarks[$mark] = $this->langArr[$mark];
		}

		return $fullLngMarks;
	}

    /**
     * replaceMarkData 
     * 
     * Replaces marks on data and returns result.
     *
     * @param string $mark 
     * @param string $data 
     * @param string $tpl 
     * @return string
     */
	protected function replaceMarkData($mark, $data, $tpl)
	{
		$tplMark = '{%'.$mark.'%}';
		$return = str_replace($tplMark,$data,$tpl);
		return $return;
	}

    /**
     * processTpl 
     * 
     * Loads and replaces data instead of mark type 'TPL'.
     * 
     * @param array $tplMarks 
     * @param string $content 
     * @return string
     */
	protected function processTpl(array $tplMarks, $content)
	{
        $tplsInnerArr = $this->loadTplFiles($tplMarks);
        
        foreach($tplsInnerArr as $key => $value)
        {
            $content = $this->replaceMarkData($key, $value, $content);
        }
        return $content;
    }

    /**
     * processStr 
     * 
     * Loads and replaces data instead of mark type 'STR'.
     *
     * @param array $strMarks 
     * @param string $content 
     * @return string
     */
    protected function processStr(array $strMarks, $content)
    {
        $fullStrMarks = $this->fillLngMarks($strMarks);

        foreach($fullStrMarks as $key => $value)
        {
            $content = $this->replaceMarkData($key, $value, $content);
        }

        return $content;
    }

    /**
     * processGen 
     * 
     * @param array $genMarks 
     * @param string $content 
     * @return string
     */
    protected function processGen (array $genMarks, $content)
    {
        foreach($genMarks as $mark)
        {
            preg_match('/^GEN_([A-Z]*).*/', $mark, $tmpMethFirst);
            preg_match('/^GEN_.*_([A-Z]*).*/', $mark, $tmpMethSecond);
            //Third ... etc;        
            
            $genMethod = 'processGen'.ucwords(strtolower(@$tmpMethFirst[1])).ucwords(strtolower(@$tmpMethSecond[1]));
            $dataToReplace = $this->{$genMethod}(@$mark);
            $content = $this->replaceMarkData($mark, $dataToReplace, $content);

        } 
        return $content;
    }
    
    /**
     * genRow 
     * 
     * Returns one row for cyclic events.
     *
     * @param string $mark 
     * @param string $data 
     * @param string $inner 
     * @return string
     */
    protected function genRow($mark, $data, $inner)
    {
        $content = $this->replaceMarkData($mark, $data, $inner);
        return $content;
    }
    
    /**
     * makeHtmlCouple 
     * 
     * Replaces two marks in tpl on key value from array.
     *
     * @param mixed $array 
     * @param mixed $tpl 
     * @return string
     */
    protected function makeHtmlCouple($array, $tpl)
    {
        $tplUrl = $this->loadTplRowFile($tpl);
        $retContent = '';
        foreach($array as $one => $two)
        {
            $content = $tplUrl;
            $content = $this->genRow('ONE', $one, $content); 
            $content = $this->genRow('TWO', $two, $content);
            $retContent .= $content;   
        }
        return $retContent;
    }

    /**
     * loadLngFiles 
     * 
     * Loads, compares, joins all language's strings.
     * Returns array with 'LNG' marks and appropriate strings.
     *
     * @return array
     */
	protected function loadLngFiles()
	{
		if(file_exists(DIR_LANGUAGES.'/'.DEF_LANGUAGE.'.language.xml'))
		{
			$defLang = simplexml_load_file(DIR_LANGUAGES.'/'.DEF_LANGUAGE.'.language.xml');

			foreach($defLang->ISTRING as $arr)
			{
				$k = (string) $arr->KEY;
				$v = (string) $arr->VALUE;

				$defLangArr[$k] = $v;
			}
		}
		else
		{
			throw new Exception (ERR_NO_LNG);
		}

		if(file_exists(DIR_LANGUAGES.'/'.$_SESSION['language'].'.language.xml'))
		{
			$thisLang = simplexml_load_file(DIR_LANGUAGES.'/'.$_SESSION['language'].'.language.xml');

			foreach($thisLang->ISTRING as $att)
			{
				$k = (string) $arr->KEY;
				$v = (string) $arr->VALUE;

				$thisLangArr[$k] = $v;
			}
		}
		else
		{
			$thisLangArr = $defLangArr;
		}

		//$this->defaultLngXml = $defLang;
		//$this->currentLngXml = $thisLang;
		foreach($defLangArr as $key => $val)
		{
			if(isset($thisLangArr[$key]))
			{
				$compLangArr[$key] = $thisLangArr[$key];
			}
			else
			{
				$compLangArr[$key] = $defLangArr[$key];
			}

		}
		return  $compLangArr;

	}

    /**
     * loadTplFiles 
     * 
     * Returns 'TPL' file's inners in array.
     *
     * @param array $tplMarks 
     * @return array
     */
	protected function loadTplFiles(array $tplMarks)
	{
		foreach($tplMarks as $mark)
		{
			preg_match('/^TPL_([A-Z]*)/', $mark, $tmpFileName); 
			$fileName = DIR_TEMPLATES.'/'.strtolower($tmpFileName[1]).'.tpl.php';
			if(file_exists($fileName))
			{
				$filesContent[$mark] = file_get_contents($fileName);
            }
            else
            {
                throw new Exception('You have to create file for '.$mark);
            }
		}
		


		return $filesContent;
    }

    /**
     * loadTplRowFile 
     * 
     * Returns inner of 'row tpl' file.
     *
     * @param string $mark 
     * @return string
     */
    protected function loadTplRowFile ($mark)
    {
        preg_match('/^GEN_.*_([A-Z]*)/', $mark, $tplFileName);
        $fileName = DIR_TEMPLATES.'/'.strtolower($tplFileName[1]).'.row.php';
        if(file_exists($fileName))
        {
            $fileInner = file_get_contents($fileName);
        }
        else 
        {
            throw new Exception('You have to create file '.strtolower($tplFileName[1]).'.row.php');
        }
        return $fileInner;
    }

/**
 * Defaults methods
 */
    protected function processGenMainContent() 
    {
        return $this->{$this->genData['view_method']}();
    }
    
    protected function notFound()
    {
        return '{%LNG_404%}';
    }

    protected function processGenMessage()
    {
        return $this->genData['messages'];
    }

 }
?>
