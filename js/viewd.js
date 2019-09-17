/**
 * @copyright Syrnik.Com, 2019
 * @author Serge Rodovnichenko, <serge@syrnik.com>
 */
function ViewdPlugin(id, type) {
    this.type = type || 'product';
    this.id = id;

    var _pong = false;

    function checkCookie() {
        var enabled = navigator.cookieEnabled;
        if(!enabled) {
            document.cookie = 'testcookie';
            enabled = document.cookie.indexOf('testcookie') !== -1;
        }

        return enabled;
    }

    this.ping = function(url) {
        if(_pong || !checkCookie()) return;
        var that = this;
        var xhr= new XMLHttpRequest();
        xhr.open('POST', url, true);
        xhr.setRequestHeader('Content-Type', 'application/json');
        xhr.onreadystatechange = function () {
            if(xhr.readyState === 4 && xhr.status === 200) {
                _pong = true;
            }
        };
        xhr.send(JSON.stringify({type: that.type, id: that.id}))
    }
}
