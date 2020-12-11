(function ($) {
    jQuery(window).on('elementor/frontend/init', function () {
        elementorFrontend.hooks.addAction('frontend/element_ready/portfolio.template', function ($scope) {
            if (!$scope.find('.elementor-portfolio').length) {
                return;
            }
            elementorFrontend.hooks.doAction('frontend/element_ready/portfolio.default', $scope, $);
        });
    });
})(jQuery, window);