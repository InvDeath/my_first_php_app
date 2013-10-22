When: 
    <input type="text" size="1" name="text_start" value="{%START_TIME%}" /> 

    <select name="s_ampm">
        <option value="am" {%S_A%}>AM</option>
        <option value="pm" {%S_P}>PM</option>
    </select>
    
    - <input type="text" size="1" name="text_finish" value="{%FINISH_TIME%}" />
    
    <select name="f_ampm">
        <option value="am" {%F_A%}>AM</option>
        <option value="pm" {%F_P%}>PM</option>
    </select>

    <input type="hidden" name="ymd" value="{%YMD%}" />
