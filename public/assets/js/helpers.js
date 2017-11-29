function ifNull(object, child, output = '-') {

    if (object !== null) {

        if (object != '') {

            if (object.hasOwnProperty(child)) {

                if (object.length != '') {

                    if (object[child] != '') {

                        return object[child];
                    } else {
                        return output;
                    }
                    
                } else {
                    return output;
                }
            }

            return object;

        } else {

            return output;
        }
    }

    return output;
}

function getParameterByName(name, url) {
    if (!url) url = window.location.href;
    name = name.replace(/[\[\]]/g, "\\$&");
    var regex = new RegExp("[?&]" + name + "(=([^&#]*)|&|#|$)"),
        results = regex.exec(url);
    if (!results) return null;
    if (!results[2]) return '';
    return decodeURIComponent(results[2].replace(/\+/g, " "));
}