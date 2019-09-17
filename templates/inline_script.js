(function (callback) {
    if(document.readyState != 'loading') callback();
    else if (document.addEventListener) document.addEventListener('DOMContentLoaded', callback);
    else document.attachEvent('onreadystatechange', function () {
            if(document.readyState == 'complete') callback();
        })
})(function () {
    var v = new ViewdPlugin($id, 'product');
    v.ping($url);
});