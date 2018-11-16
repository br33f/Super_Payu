function checkStatus() {
    var xhr = new XMLHttpRequest();
    xhr.open('GET', paymentStatusCheckUrl);
    xhr.send(null);

    xhr.onreadystatechange = function () {
        var DONE = 4;
        var OK = 200;
        if (xhr.readyState === DONE) {
            if (xhr.status === OK) {
                var paymentStatus = xhr.responseText;
                switch (paymentStatus) {
                    case "payment-success": case "payment-fail":
                        clearInterval(statusCheckInterval);
                        document.querySelector('.super-payments-continue').classList.remove('payment-waiting');
                        document.querySelector('.super-payments-continue').classList.add(paymentStatus);
                        break;
                    case "payment-waiting":
                        break;
                }
            } else {
                console.log('Error: ' + xhr.status);
            }
        }
    }
}

var statusCheckInterval = null;
window.addEventListener('load', function() {
    var intervalValue = 3000;
    statusCheckInterval = setInterval(checkStatus, intervalValue);
});
