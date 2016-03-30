angular.module('helpers', [])
.factory('helpers', function() { return {
    findBy: function (source, value, searchBy) {
        searchBy = searchBy ? searchBy : 'id';
        for (var i in source) {
            if (source[i][searchBy] === value) {
                return source[i];
            }
        }
    },

    findIdBy: function (source, value, searchBy) {
        searchBy = searchBy ? searchBy : 'id';
        for (var i in source) {
            if (source[i][searchBy] === value) {
                return i;
            }
        }
    },

    formatDT: function (time, format) {
        format = format ? format : 'MM-DD-YYYY';
        return moment(time).format(format);
    },

    getSortingParams: function (sorting) {
        if (typeof sorting != 'undefined') {
            var key = Object.keys(sorting)[0];
            return {
                by: key,
                direction: sorting[key]
            }
        }
    },

    scrollToTop: function () {
        $("html, body").animate({
            scrollTop: 0
        }, 600);
    },

    capitalizeFirstLetter: function (string) {
        return string.charAt(0).toUpperCase() + string.slice(1);
    },

    pad: function(n, width, z) {
        z = z || '0';
        n = n + '';
        return n.length >= width ? n : new Array(width - n.length + 1).join(z) + n;
    }
}});