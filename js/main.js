// const api_url = 'http://localhost/email-marketing/api/';
// Function to create the cookie
const home_url = 'http://localhost/email-marketing/';
const dashboard_url = 'http://localhost/email-marketing/dashboard/';
const api_url = 'http://localhost/email-marketing/api/';
function createCookie(name, value, days) {
    var expires;
      
    if (days) {
        var date = new Date();
        date.setTime(date.getTime() + (days * 24 * 60 * 60 * 1000));
        expires = "; expires=" + date.toGMTString();
    }
    else {
        expires = "";
    }
      
    document.cookie = escape(name) + "=" + 
        escape(value) + expires + "; path=/";
}