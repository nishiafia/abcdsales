//Vue Filter to make first letter Capital
Vue.filter("strToUpper", function(text) {

    return text.charAt(0).toUpperCase() + text.slice(1);

});

//Vue moment js to show human readable date
import moment from "moment"; //Import Moment

Vue.filter("formatDate", function(date) {

    //return moment(date).format('MMMM Do YYYY');
    return moment(date).format('D-MMM-Y');

}); 

Vue.filter('toCurrency', function (value) {
    if (typeof value !== "number") {
        return value;
    }
    var formatter = new Intl.NumberFormat('en-US', {
        style: 'currency',
        currency: 'BDT',
        minimumFractionDigits: 2
    });
    return formatter.format(value);
});

Vue.filter("truncate", function(value) {

    //return moment(date).format('MMMM Do YYYY');
        if (value && value.length> 50) {
            value = value.substring(0, 45) + '...';
        }
        return value

});
