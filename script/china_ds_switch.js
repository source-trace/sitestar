function clearSelect(elem) {
    var select_elem = document.getElementById(elem);
    for (var i = select_elem.options.length; i >= 0; i--) {
        select_elem.remove(i);
    }
}

function fillProvince(elem, selected) {
    var prov_elem = document.getElementById(elem);
    prov_elem.options[0] = new Option("--", "");
    
    for (var prov in ds_data) {
        if (ds_data[prov][1] == "1") {
            if (selected == prov) {
                prov_elem.options[prov_elem.options.length] = new Option(ds_data[prov][0], prov, false, true);
            } else {
                prov_elem.options[prov_elem.options.length] = new Option(ds_data[prov][0], prov);
            }
        }
    }
}

function fillCity(elem, province, selected) {
    clearSelect(elem);
    if (/^\s*$/.test(province)) return;
    var city_elem = document.getElementById(elem);
    city_elem.options[0] = new Option("--", "");

    for (var city in ds_data) {
        if (ds_data[city][1] == province) {
            if (selected == city) {
                city_elem.options[city_elem.options.length] = new Option(ds_data[city][0], city, false, true);
            } else {
                city_elem.options[city_elem.options.length] = new Option(ds_data[city][0], city);
            }
        }
    }
}

function fillDist(elem, city, selected) {
    clearSelect(elem);
    if (/^\s*$/.test(city)) return;
    var dist_elem = document.getElementById(elem);
    dist_elem.options[0] = new Option("--", "");

    for (var dist in ds_data) {
        if (ds_data[dist][1] == city) {
            if (selected == dist) {
                dist_elem.options[dist_elem.options.length] = new Option(ds_data[dist][0], dist, false, true);
            } else {
                dist_elem.options[dist_elem.options.length] = new Option(ds_data[dist][0], dist);
            }
        }
    }
}
