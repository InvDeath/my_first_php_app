
/**
 * changeDays 
 * 
 * Replaces list of days for current month/year.
 *
 * @return void
 */
function changeDays()
{
    var year = document.getElementById('year').value;
    var month = document.getElementById('month').value;
    countDays = new Date(year, month, 0).getDate();
    
    var select = document.getElementById('day');
    select.innerHTML = '';       
    for (var i = 1; i <= countDays; i++)
    {
        var option = document.createElement('option');
        option.innerHTML = i;
        option.value = i;
        select.appendChild(option);
    }

}
