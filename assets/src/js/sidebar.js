var sidebar = {
    init: function() {
        $('#sidebar-collapse').on('click', this.show);
        $('#sidebar-dismiss, .sidebar-overlay').on('click', this.hide);
        $(window).on('keydown', this.keydown);
    },
    show: function() {
        $('.sidebar-wrap').addClass('active');
    },
    hide: function() {
        $('.sidebar-wrap').removeClass('active');
    },
    keydown: function(e) {
        var $sidebar;
        if (e.which == 27 && $('.sidebar-wrap').hasClass('active')) {
            sidebar.hide();
        }
    }
};

sidebar.init();
