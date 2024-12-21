document.addEventListener('DOMContentLoaded', function () {
    var referrer = document.referrer;
    if (referrer) {
        localStorage.setItem('landingPageReferrer', referrer);

        var xhr = new XMLHttpRequest();
        xhr.open('POST', myAjax.ajaxurl, true);
        xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=UTF-8');
        xhr.send('action=store_referrer&referrer=' + encodeURIComponent(referrer));

    }
});
