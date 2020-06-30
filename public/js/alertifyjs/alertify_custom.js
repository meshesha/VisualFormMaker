if (!alertify.errorMsg) {
    //define a new dialog
    alertify.dialog('errorMsg', function factory() {
        return {
            main: function (message) {
                this.message = "<span>" + message + "</span>";
            },
            prepare: function () {
                this.setContent(this.message);
            },
            build: function () {
                var errorHeader = '<span class="fa fa-times-circle fa-2x" '
                    + 'style="vertical-align:middle;color:#e10000;">'
                    + '</span> Error';
                this.setHeader(errorHeader);
                $(this.elements.body).addClass("bg-danger text-white")
            }
        };
    }, true, 'alert');
}

if (!alertify.warningMsg) {
    //define a new dialog
    alertify.dialog('warningMsg', function factory() {
        return {
            main: function (message) {
                this.message = "<span>" + message + "</span>";
            },
            prepare: function () {
                this.setContent(this.message);
            },
            build: function () {
                var errorHeader = '<span class="fa fa-exclamation-triangle fa-2x" '
                    + 'style="vertical-align:middle;color:#ffcc00;">'
                    + '</span> Warning';
                this.setHeader(errorHeader);
                $(this.elements.body).addClass("bg-warning text-dark")
            }
        };
    }, true, 'alert');
}


if (!alertify.successMsg) {
    //define a new dialog
    alertify.dialog('successMsg', function factory() {
        return {
            main: function (message) {
                this.message = "<span>" + message + "</span>";
            },
            prepare: function () {
                this.setContent(this.message);
            },
            build: function () {
                var errorHeader = '<span class="fa fa-check-circle fa-2x" '
                    + 'style="vertical-align:middle;color:#339900;">'
                    + '</span> Success';
                this.setHeader(errorHeader);
                $(this.elements.body).addClass("bg-success text-white")
            }
        };
    }, true, 'alert');
}