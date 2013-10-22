<h1>Boadroom Booker</h1> <span>{%GEN_BOOKIT_CURROOM%}</span>
<form action="?page=bookit&action=addEvent" method="post">
    <label>1. Booked for:</label>
    <br />
    
    <select name="employee">
        {%GEN_BOOKIT_EMPLOYEES%}
    </select>
    <br /><br />
                    
    <label>2. I would like to book this meeting:</label>
    <br />
    <select id="month" name="month" onchange="changeDays();">
        {%GEN_BOOKIT_MONTHS%}
    </select>
    
    <select id="day" name="day">
        {%GEN_BOOKIT_DAYS%}
    </select>
    
    <select id="year" name="year" onchange="changeDays();">
        {%GEN_BOOKIT_YEARS%}
    </select>
    <br />
                                    
    <label>3. Specify what the and end of the meeting(This will be what people see when they click on an event link.)</label>
    <br />
    
    {%GEN_BOOKIT_TIME%}               
    <br /><br />
    
    <label>4. Enter the specifics for the mmeting.(This will be what people see when they click on an event link.)</label>
    <br />
                    
    <textarea name="description" rows="5" cols="30"></textarea><br /><br />
    <label>5. Is this going to be a recurring event?</label><br />
    <input name="reccuring" value="no" type="radio" CHECKED> <label>no</label><br>
    <input name="reccuring" value="yes" type="radio"> <label>yes</label><br><br>
    <label>6. If it is recurring, specify weekly, bi-weekly, or mothly</label><br>
    <input name="recurring_type" value="weekly" type="radio"> <label>weekly</label><br>
    <input name="recurring_type" value="bi_weekly" type="radio"> <label>bi_weekly</label><br>
    <input name="recurring_type" value="monthly" type="radio"> <label>monthly</label><br><br>
    <label>If weekly or bi-weekly, specify the number of weeks for it to keep recurring. If monthly, specify the number of months.(If you choose "be-weekly" and put in an odd number of weeks, the computer will round down)</label><br>
    <input value="" name="reccuring_value" type="text"><br><br>
    <input value="Submit" name="book_it" type="submit">
                
</form>
