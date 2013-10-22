<h2>{%LNG_BB_DETAILS%}</h2>
<form action="?page=event&action=editEvent&id={%GEN_EVENT_ID%}" method="post">
{%GEN_EVENT_TIME%}<br />
{%GEN_EVENT_NOTES%}<br />
<select name="employee">
    {%GEN_EVENT_WHO%}
</select><br />
{%GEN_EVENT_SUBMITTED%} <br />
{%GEN_EVENT_RECCURING%}<br />


<input type="submit" name="update" value="Update" />
<input type="submit" name="remove" value="Remove" />

</form>


