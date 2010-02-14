function rate(rating)
{
    for (var i = 1; i < rating+1; i++) {
        document.getElementById(i).checked = 'checked';
    }
    for (var i = rating+1; i < 6; i++) {
        document.getElementById(i).checked = '';
    }
}